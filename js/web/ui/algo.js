/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TAlgo = function() {
    TWebObject.call(this);
    
};

TAlgo.prototype = new TWebObject();
TAlgo.prototype.constructor = TAlgo;

TAlgo.create = function() {
    return new TAlgo();
}

TAlgo.prototype.applyTemplate = function(templates, colNum, row, i) {
    var html = row[i];
    var template = templates[i];

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

    return html;
}

TAlgo.prototype.dataBind = function(tableId, values, templates) {
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
}