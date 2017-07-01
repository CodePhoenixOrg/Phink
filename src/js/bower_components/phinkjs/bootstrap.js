'use strict';
let path = require('path');
let fs = require('fs');

let folders = __dirname.split(path.sep);

global.PHINK_ROOT = folders.join(path.sep) + path.sep;

folders.pop();
folders.pop();

global.SITE_ROOT = folders.join(path.sep) + path.sep;
global.APP_ROOT = global.SITE_ROOT + 'app' + path.sep;
global.APP_DATA = global.APP_ROOT + 'data' + path.sep;
global.APP_MODELS = global.APP_ROOT + 'models' + path.sep;
global.APP_CONTROLLERS = global.APP_ROOT + 'controllers' + path.sep;
global.APP_VIEWS = global.APP_ROOT + 'views' + path.sep;
global.DOCUMENT_ROOT = global.SITE_ROOT + 'web' + path.sep;
global.DIRECTORY_SEPARATOR = path.sep;

let BootStrap = function () { };

BootStrap.init = function () {

    let _concat = function (srcdir, srctree, destfile) {
        let content = "";

        for (let i = 0; i < srctree.length; i++) {
            content += fs.readFileSync(srcdir + srctree[i]) + "\n";
        }
        fs.writeFileSync(destfile, content, { encoding: 'utf-8', mode: 0o666, flag: 'w' });

    }

    let outfile = global.SITE_ROOT + "vendor" + path.sep + "phink" + path.sep + "phink.js";

    let dir = global.PHINK_ROOT + "client" + path.sep;
    let tree = [
        "main.js",
        "core" + path.sep + "url.js",
        "core" + path.sep + "registry.js",
        "utils" + path.sep + "text.js",
        "core" + path.sep + "object.js",
        "web" + path.sep + "web_object.js",
        "web" + path.sep + "web_application.js",
        "web" + path.sep + "rest.js",
        "mvc" + path.sep + "view.js",
        "mvc" + path.sep + "controller.js",
        "web" + path.sep + "ui" + path.sep + "plugin.js",
        "web" + path.sep + "ui" + path.sep + "plugin" + path.sep + "accordion.js",
        "web" + path.sep + "ui" + path.sep + "plugin" + path.sep + "list.js",
        "web" + path.sep + "ui" + path.sep + "plugin" + path.sep + "table.js"
    ];

    _concat(dir, tree, outfile);

}

BootStrap.init();

module.exports = BootStrap;
