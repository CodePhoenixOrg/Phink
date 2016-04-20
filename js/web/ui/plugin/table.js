/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var TTable = function() {
    TPlugin.call(this);
};

TTable.prototype = new TPlugin();
TTable.prototype.constructor = TTable;

TTable.create = function() {
    return new TTable();
};
    
TTable.prototype.bind = function(tableId, values, templates) {
    var colNum = templates.length;
    var rowNum = values.length;
    for(var j=0; j < rowNum; j++) {
        var row = JSON.parse(values[j]);
        for (var i=0; i < colNum; i++) {
            var template = templates[i];
            var html = TPlugin.applyTemplate(templates, row, i);
            if(template.enabled) {
                $(tableId + 'td' + (i + colNum * j).toString()).html(html);
            }
        }
    }
};

