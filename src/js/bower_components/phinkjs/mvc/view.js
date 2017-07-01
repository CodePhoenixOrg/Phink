'use strict';
let PhinkJSWebObject = require('../web/web_object.js');

class PhinkJSMVCView extends PhinkJSWebObject {

    constructor(parent, viewName) {
        super(parent);
        
        this._viewName = viewName;
        this._viewFileName = global.APP_VIEWS + viewName + '.html';

    }

    getTemplate(callback) {
        require('fs').readFile(this._viewFileName, 'utf-8', function (err, data) {
            callback(err, data);
        });
    }
}

module.exports = PhinkJSMVCView;