'use strict';
let PhinkJSWebObject = require('../web/web_object.js');
let PhinkJSRegistry = require('./registry.js');

const WEB = 'web';
const REST = 'rest';

class PhinkJSRouter extends PhinkJSWebObject {

    constructor(parent, req, res) {
        if (parent instanceof PhinkJSRouter) {
            super(parent.parent);
            this._request = parent.request;
            this._response = parent.response;
            this._translation = parent.translation;
            this._requestType = parent.requestType;
            this._parameters = parent.parameters;
            this._className = parent.className;
            // console.log("PARAMETERS PARENT");
            // console.log({'parent': [parent.request.method, parent.parameters, parent.translation, parent.requestType, parent.className]});
            // console.log("END PARAMETERS PARENT");

        } else {
            super(parent);
            this._request = req;
            this._response = res;
            this._translation = '';
            this._requestType = WEB;
            this._className = '';
            this._parameters = null;
        }



        this._mimetype = '';
        this._encoding = '';
        this._routes = null;
    }

    get className() {
        return this._className;
    }

    get requestType() {
        return this._requestType;
    }

    get request() {
        return this._request;
    }

    get response() {
        return this._response;
    }

    get translation() {
        return this._translation;
    }

    get parameters() {
        return this._parameters;
    }
    set parameters(parameters) {
        this._parameters = parameters;
    }

    get mimeType() {
        return this._mimetype;
    }

    get encoding() {
        return this._encoding;
    }

    match() {
        let result = 'web';
        let self = this;

        if (this.routes) {

            Object.keys(this._routes).forEach(function (reqtype) {
                var methods = self._routes[reqtype];
                var method = self._request.method.toLowerCase();

                if (methods[method] !== undefined) {
                    const routes = methods[method];
                    const url = self._request.url;
                    Object.keys(routes).forEach(function (key) {
                        const matches = url.replace(new RegExp(key, 'g'), routes[key]);

                        if (matches !== url) {
                            self._requestType = reqtype;
                            self._translation = matches;
                            let baseurl = require('url').parse(self._translation, true);
                            self._className = require('path').basename(baseurl.pathname);
                            self._parameters = self._parameters || {};
                            Object.assign(self._parameters, baseurl.query);

                            return reqtype;
                        }
                    });
                }

            });
        }

        if (this._translation === '') {
            this._requestType = 'web';
            result = 'web';
        }

        return result;
    }

    get routes() {
        let _routes = PhinkJSRegistry.item('routes');

        if (_routes.length === undefined) {
            if (require('fs').existsSync(global.SITE_ROOT + 'routes.json')) {
                _routes = require('fs').readFileSync(global.SITE_ROOT + 'routes.json');
                _routes = _routes.toString();
            }
            if (_routes.length > 0) {
                _routes = JSON.parse(_routes);
                Object.keys(_routes).forEach(function (key) {
                    const value = _routes[key];
                    PhinkJSRegistry.write('routes', key, value);
                });
            }
        }

        this._routes = _routes;

        return _routes;
    }

    translate(callback) {}

    dispatch(callback) {}
}

module.exports = PhinkJSRouter;