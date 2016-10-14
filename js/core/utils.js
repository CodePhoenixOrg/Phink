/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var TUtils = function() {
    
};

TUtils.find = function(haystack, index, needle) {
    var result = [];

    if(haystack.length === 0) return result;
    var first = JSON.parse(haystack[0]);
    if(first.length < index - 1) return result;

    for( var k = 0; k < haystack.length; ++k ) {
        var row = JSON.parse(haystack[k]);
        if( needle == row[index] ) {
            result = row;
            break;
        }
    }        

    return result;
};

/**
 * 
 * @param {type} haystack
 * @param {type} key
 * @param {type} needle
 * @returns {Array|TUtils.grep.haystack}
 */
TUtils.grep = function(haystack, key, needle) {
    var result = [];

    if(haystack.length === 0) return result;
    var first = JSON.parse(haystack[0]);
    if(!first.hasOwnProperty(key)) return result;

    for( var k = 0; k < haystack.length; ++k ) {
        var row = JSON.parse(haystack[k]);
        if( needle == row[key] ) {
            result = row;
            break;
        }
    }        

    return result;
};

TUtils.resizeIframe = function(ui) {
    ui.style.height = ui.contentWindow.document.body.scrollHeight + 'px';
};

TUtils.html64 = function(container, html) {
    $(container).html(base64_decode(html));
};

TUtils.secondsToString = function(seconds) {
     var minutes = Math.floor(seconds / 60)
     var seconds = seconds - (minutes * 60)
     
     return minutes + ':' + ('00' + seconds).toString().slice(-2)
}

function debugLog(message) {
    alert(message);
}