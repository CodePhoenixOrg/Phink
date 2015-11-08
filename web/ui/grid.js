
function fillTable1(tableId, cols, rows, values) {
    for(var j=0; j < rows; j++) {
        var row = JSON.parse(values[j]);
        for (var i=0; i < cols; i++) {
            $(tableId + 'td' + (i + cols * j).toString()).html(row[i]);
        }
    }
}
