'use strict';
let PhinkJSObject = require(__dirname + '/../core/object.js');

class PhinkJSWebObject extends PhinkJSObject {
    constructor(parent) {
        super(parent)

    }

    static include(file, encoding, callback) {
        require('fs').readFile(file, encoding, function (err, stream) {
            if (typeof callback === 'function') {
                callback(err, stream);
            }

        });
    }
}

module.exports = PhinkJSWebObject;