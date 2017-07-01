'use strict';
let PhinkJSWebObject = require('../web/web_object.js');

class PhinkJSMVCController extends PhinkJSWebObject {
    constructor(parent, viewName) {
        super(parent);
        
        this._viewName = viewName;
        this._view = new (require(global.PHINK_ROOT + 'mvc/view'))(parent, viewName);
    }

    load() { }

    render(callback) {
        console.log("RENDER");

        // this._load();
        let the = this;
        this.load(function(ready) { 
            the.parse(ready, function (data) {
                callback(data);
            });
        });
    }

    parse(ready, callback) {
        console.log("PARSE");
        let the = this;
        this._view.getTemplate(function (err, template) {
            // console.log(template);
            let matches = template.match(/(\<% [a-z]+ %\>)/g);

            if (matches) {
                console.log(matches);
                matches.forEach(function (match) {
                    let variable = match.replace("%>", '').replace("<%", '').trim();

                    console.log('match: ' + match);
                    console.log('variable: ' + variable);
                    console.log(the[variable]);
                    // console.log(html);
                    template = template.replace(match, the[variable]);
                    //template = template.replace(match, html);
                });
            }
            // console.log(template);

            callback(template);

        });

    }
}
module.exports = PhinkJSMVCController;