/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TWebObject = function() {
    this.origin = '';
    this.url = {};
    
};

TWebObject.prototype.setOrigin = function(value) {
    this.origin = value;
}

TWebObject.prototype.getOrigin = function() {
    return this.origin;
}

TWebObject.parseUrl = function (url) {

    var result = {};

    var protocolLess = url.replace('http://', '');
    var hasProtocol = protocolLess.length < url.length;
    var protocol = '';
//                var isSecure = false;
    if(!hasProtocol) {
        protocolLess = url.replace('https://', '');
        hasProtocol = protocolLess.length < url.length;
        if(hasProtocol) {
            isSecure = true;
            protocol = 'https://';
        }
    } else {
        protocol = 'http://';
    }

    result.protocol = protocol;
    url = url.replace(protocol, '');

    var domainLimit = url.indexOf('/');
    var domain = url;
    var queryString = '';
    if(domainLimit > -1) {
        domain = url.substring(0, domainLimit);
        url = url.replace(domain, '');
    } else {
        domain = '';
    }

    result.domain = domain;
    result.page = url.replace('.html', '');

    console.log(result);
    this.url = result;

    return result;
};


TWebObject.prototype.getUrl = function() {
    return this.url;
}
