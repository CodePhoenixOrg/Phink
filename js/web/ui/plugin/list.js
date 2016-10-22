/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TList = function() {
    TPlugin.call(this);
    
    
};

TList.prototype = new TPlugin();
TList.prototype.constructor = TList;

TList.create = function() {
    return new TList();
};


TList.prototype.bind = function(container, names, values, templates, elements, callback) {
    var colNum = templates.length;
    var rowNum = values.length;

    var result = '';
    var html = '';
    var row = 0;
    var css = '';

    result = str_replace('%s', css, elements[0].opening) + "\n";
    var oldValue = [];
    
    for(i = 0; i < rowNum; i++) {

        row = (values[i] !== null) ? JSON.parse(values[i]) : Array.apply(null, Array(colNum)).map(String.prototype.valueOf, '&nbsp;');

        result += str_replace('%s', '', elements[1].opening) + "\n";
        for(j = 0; j < colNum; j++) {
            var k = i * colNum + j;
            html = TPlugin.applyTemplate(templates, row, j);
            if(templates[j]['enabled'] == 1 && row[j] != oldValue[j]) {
                result += str_replace('%s', '', elements[2].opening) + html + elements[2].closing + "\n";
            }
            oldValue[j] = row[j];
        }
        result += elements[1].closing + "\n";
    }
    result += elements[0].closing + "\n";

    $(container).html("&nbsp;");
    $(container).html(result);
    
    if(typeof callback === 'function') {
        callback.call(this);
    }
};


    
