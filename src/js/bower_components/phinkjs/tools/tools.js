'use strict';


var Tools = function () {};

Tools.listFiles = function () {

    var path = require('path');
    var fs = require('fs');

    var walkTree = function (dir, callback) {
        var results = [];
        fs.readdir(dir, function (err, list) {
            if (err) return callback.call(err);
            var pending = list.length;
            if (pending === 0) return callback.call(null, results);

            list.forEach(function (file) {
                file = path.resolve(dir, file);
                fs.stat(file, function (err, stat) {
                    if (stat && stat.isDirectory()) {
                        pending--;
                        walkTree(file, callback);
                    } else {
                        results.push(file);
                        pending--;
                        if (pending === 0) callback.call(null, results);
                    }
                });
            });
        });
    };

    var tree = [];
    var dir = __dirname + path.sep + '.' + path.sep;
    walkTree(dir, function (files) {
        for (var i = 0; i < files.length; i++) {
            var file = path.basename(files[i]);
            var dir = path.dirname(files[i]).substring(__dirname.length);

            console.log('"' + files[i].substring(__dirname.length) + '", ');

        }

    })

}


module.exports = Tools;