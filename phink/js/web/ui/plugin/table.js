var Phink = Phink || {}
Phink.Web = Phink.Web || {}
Phink.Web.UI = Phink.Web.UI || {}

Phink.Web.UI.Table = function() {
    Phink.Web.UI.Plugin.call(this);
};

Phink.Web.UI.Table.prototype = new Phink.Web.UI.Plugin();
Phink.Web.UI.Table.prototype.constructor = Phink.Web.UI.Table;

Phink.Web.UI.Table.create = function() {
    return new Phink.Web.UI.Table();
};
    
Phink.Web.UI.Table.prototype.bind = function(tableId, data, callback) {
    var values = data.values;
    var templates = data.templates;
    var colNum = templates.length;
    var rowNum = values.length;
    for(var j=0; j < rowNum; j++) {
        var row = JSON.parse(values[j]);
        for (var i=0; i < colNum; i++) {
            var template = templates[i];
            var html = Phink.Web.UI.Plugin.applyTemplate(templates, row, i);
            if(template.enabled) {
                $(tableId + 'td' + (i + colNum * j).toString()).html(html);
            }
        }
    }
    
    if(typeof callback === 'function') {
        callback.call(this);
    }
};

