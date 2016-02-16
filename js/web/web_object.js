/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TWebObject = function() {
    this.origin = '';
    this.url = {};
    this.token = '';
};

TWebObject.prototype.setOrigin = function(value) {
    this.origin = value;
    
    return this;
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

TWebObject.prototype.getJSON = function(
    url, // Url du webService
    postData, // Tableau JSON des données à poster au webserice
    callBack // fonction qui gère le retour du webservice
) {
    //$("body").toggleClass('onLoad');
//        spinner.spin();
    postData.token = this.token;
    
//    var url = TWebObject.parseUrl(url);
//    url = (this.origin !== undefined) ? this.origin + url : url;

    $.ajax({
        type: 'POST',
        url: url,
        data: postData,
        dataType: 'json',
        async: true
    }).done(function(data, textStatus, xhr) {
        try 
        {
            this.token = data.token;
            url = TWebObject.parseUrl(url);
            TRegistry.item(url.page).origin = xhr.getResponseHeader('origin');
            
            if($.isFunction(callBack)) {
                callBack.call(this, data, textStatus, xhr);
            }

        }
        catch(e)
        {
            debugLog(e);
        }
    }).fail(function(xhr, options, message) {
        debugLog("Satus : " + xhr.status + "\r\n" +
                "Options : " + options + "\r\n" +
                "Message : " + message);
    });
};

TWebObject.prototype.getJSONP = function(url, postData, callBack) {
    postData.token = this.token;

    $.ajax({
        type: 'POST',
        url: url + "&callback=?", // retour en JSONP
        data: postData,
        dataType: 'json',
        async: true
    }).done(function(data, textStatus, xhr) {
        try {
            this.token = data.token;
            url = TWebObject.parseUrl(url);
            TRegistry.item(url.page).origin = xhr.getResponseHeader('origin');

            if($.isFunction(callBack)) {
                callBack.call(this, data, textStatus, xhr);
            }
        }
        catch(e) {
            debugLog(e);
        }
    }).fail(function(xhr, options, message) {
        debugLog("Satus : " + xhr.status + "\r\n" +
            "Options : " + options + "\r\n" +
            "Message : " + message);
    });
};

