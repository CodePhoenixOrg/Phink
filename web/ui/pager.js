var index = '<% pageNum %>';
var count = '<% pageCount %>';
index = (parseInt(index)) ? parseInt(index) : 1;
count = (parseInt(count)) ? parseInt(count) : 20;

function fastLeft() {
    index--;
    index = (index < 1) ? 1 : index;
    <% onclick %>(count, index, '#<% id %>pageNum');
}

function fastRight(){
    index++;
    index = (index > 999) ? 1 : index;
    <% onclick %>(count, index, '#<% id %>pageNum');        
}

function leftLimit() {
    index = 1;
    <% onclick %>(count, index, '#<% id %>pageNum');        
}

function rightLimit() {
    index = 999;
    <% onclick %>(count, index, '#<% id %>pageNum');        
}

