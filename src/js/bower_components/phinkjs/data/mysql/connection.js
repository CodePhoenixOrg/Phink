'use strict';

let mysql = require('mysql');
let tunnel = require('tunnel-ssh');
let fs = require('fs');

class PhinkJSMySQLConnection {
    constructor(config) {
        this.config = config;
    }

    direct() {
        _conn = new mysql.createConnection(this.config);
        return _conn;
    }

    tunneled(sshConfig, callback) {

        let key = fs.readFileSync(APP_DATA + "/ssh/id_rsa");

        // let config = {
        //     username: "lambda",
        //     host: "192.168.1.150:22",
        //     privateKey: key,
        //     dstPort: 3306
        // };

        tunnel(sshConfig, function (err, server) {
            if (err) {
                return console.log(err);
            }
            // let _conn = new mysql.createConnection({
            //     host: 'localhost',
            //     port: 3307,
            //     user: 'djay',
            //     password: 'demo',
            //     database: 'soundlib'
            // });
            let _conn = null;


            if (typeof callback === 'function') {
                callback.call(null, _conn, server);
            }
        });

        // let _conn = mysql.createConnection({
        //     host: 'localhost',
        //     user: 'djay',
        //     password: 'demo',
        //     database: 'soundlib'
        // });




    }
}

module.exports = SoundLib.Data.Connection;