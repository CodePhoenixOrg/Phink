/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TPlugin = function() {
    TWebObject.call(this);
    
};

TPlugin.prototype = new TWebObject();
TPlugin.prototype.constructor = TPlugin;

TPlugin.create = function() {
    return new TPlugin();
};

TPlugin.applyTemplate = function(templates, row, i) {
    var html = row[i];
    
//    if(templates[i] === undefined) {
//        return html;
//    }
    
    if(templates[i].content !== '' && templates[i].enabled) {
        html = templates[i].content;
        var event = templates[i].event;
        var e = event.split('#');
        if(e[0] === 'href') {
            event = 'javascript:' + e[1];
        } else {
            event = e[0] + '="' + e[1] + '"'; 
        }
        for (var m = 0; m < templates.length; m++) {
//            if(templates[m] === undefined) continue;
            html = html.replace('<% ' + templates[m].name + ' %>', row[m]);
            html = html.replace('<% ' + templates[m].name + ':index %>', m);
            event = event.replace(templates[m].name, "'" + row[m] + "'");
            html = html.replace('<% &' + templates[m].name + ' %>', event);
        }   
    }
    
    return html;
};

TPlugin.applyDragHelper = function(templates, row, i) {
    var html = row[i];
    
    if(templates[i].dragHelper !== '' && templates[i].enabled) {
        html = templates[i].dragHelper;
        var event = templates[i].event;
        var e = event.split('#');
        if(e[0] === 'href') {
            event = 'javascript:' + e[1];
        } else {
            event = e[0] + '="' + e[1] + '"'; 
        }
        for (var m = 0; m < row.length; m++) {
            html = html.replace('<% ' + templates[m].name + ' %>', row[m]);
            html = html.replace('<% ' + templates[m].name + ':index %>', m);
            event = event.replace(templates[m].name, "'" + row[m] + "'");
            html = html.replace('<% &' + templates[m].name + ' %>', event);
        }   
    }

    return html;
};

TPlugin.prototype.dataBind = function(tableId, values, templates) {
    var colNum = templates.length;
    var rowNum = values.length;
    for(var j=0; j < rowNum; j++) {
        var row = JSON.parse(values[j]);
        for (var i=0; i < colNum; i++) {
            var template = templates[i];
            var html = row[i];

            if(template.content !== null && template.enabled) {
                html = template.content;
                var event = template.event;
                var e = event.split('#');
                if(e[0] === 'href') {
                    event = 'javascript:' + e[1];
                } else {
                    event = e[0] + '="' + e[1] + '"'; 
                }
                for (var m = 0; m < colNum; m++) {
                    html = html.replace('<% ' + templates[m].name + ' %>', row[m]);
                    event = event.replace(templates[m].name, row[m]);
                    html = html.replace('<% &' + templates[m].name + ' %>', event);
                }    
            }
            if(template.enabled) {
                $(tableId + 'td' + (i + colNum * j).toString()).html(html);
            }
        }
    }
};