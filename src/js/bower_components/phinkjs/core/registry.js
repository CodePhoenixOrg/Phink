'use strict';


let Registry = new (class {

    constructor() {
        this._registry = {};
    }

    write(item, key, value) {

        if (this._registry[item] === undefined) {
            this._registry[item] = {};
        }
        this._registry[item][key] = value;

    }

    read(item, key, defaultValue) {
        var result = null;

        if (this._registry[item] !== undefined) {
            result = (this._registry[item][key] !== undefined) ? this._registry[item][key] : ((defaultValue !== undefined) ? defaultValue : null);
        }

        return result;
    }

    item(item) {
        if (item === '' || item === undefined) return null;

        if (this._registry[item] !== undefined) {
            return this._registry[item];
        } else {
            this._registry[item] = {};
            return this._registry[item];
        }
    }

    items() {
        return this._registry;
    }

    clear() {
        this._registry = {};
    }

    setToken(value) {
        this._registry['token'] = value;

        return this;
    };

    getToken() {
        return this._registry['token'];
    };

    setOrigin(value) {
        this._registry['origin'] = value;

        return this;
    };

    getOrigin() {
        return this._registry['origin'];
    };


})();

module.exports = Registry;