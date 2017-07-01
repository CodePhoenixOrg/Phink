'use strict';
let PhinkJSRouter = require('../core/base_router.js');

class PhinkJSWebRouter extends PhinkJSRouter {
    constructor(parent, req, res) {
        super(parent, req, res);

        this._filePath = '';
        this._extension = '';
        this._viewName = '';

    }

    translate(callback) {

        let url = (this._className !== '') ? this._className : (this._request.url === '/') ? 'index.html' : this._request.url;
        let baseurl = require('url').parse(url);
        this._viewName = require('path').basename(baseurl.pathname);
        this._extension = require('path').extname(this._viewName);

        let dotoffset = this._viewName.lastIndexOf('.');
        this._viewName = (dotoffset > -1) ? this._viewName.substring(0, dotoffset) : this._viewName;
        let mime = (this._extension === '') ? ['text/plain', 'utf-8'] : {
            '.html': ['text/html', 'utf-8'],
            '.css': ['text/css', 'utf-8'],
            '.js': ['application/javascript', 'utf-8'],
            '.json': ['application/json', 'utf-8'],
            '.xml': ['application/xml', 'utf-8'],
            '.zip': ['application/zip', ''],
            '.ico': ['image/vnd.microsoft.icon', ''],
            '.jpg': ['image/jpg', ''],
            '.png': ['image/png', '']
        }[this._extension];

        this._encoding = mime[1];
        this._mimetype = mime[0];

        this._filePath = (this._extension === '.html') ? global.APP_VIEWS + url : (this._extension === '.js' && url.lastIndexOf('/phink.js') > -1) ? global.PHINK_ROOT + 'phink.js' : global.DOCUMENT_ROOT + url;

        require('fs').exists(this._filePath, function (exists) {
            callback(exists);
        });
    }

    dispatch(callback) {
        let encoding = (this._encoding !== '') ? {
            'encoding': this._encoding
        } : null;
        let res = this._response;
        let req = this._request;
        let mime = this._mimetype;

        if (this._extension === '.html' && require('fs').existsSync(global.APP_CONTROLLERS + this._viewName + '.js')) {
            let Controller = require(global.APP_CONTROLLERS + this._viewName);
            let ctrl = new Controller(this, this._viewName);
            ctrl.render(function (stream) {
                res.writeHead(200, {
                    'Content-Type': mime
                });
                callback(req, res, stream);
            });
            return true;
        }
 
        require('./web_object').include(this._filePath, encoding, function (err, stream) {
            if (!err) {

                res.writeHead(200, {
                    'Content-Type': mime
                });
                if (typeof callback === 'function') {
                    callback(req, res, stream);
                }
            } else {
                console.log(err);
            }
        })

    }
}

module.exports = PhinkJSWebRouter;