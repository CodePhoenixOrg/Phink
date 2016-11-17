/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TWebObject = function(domain, isSSL) {
    TObject.call(this);
    
    this.isSSL = isSSL;
    this.origin = '';
    this.url = {};
    this.token = '';
    this.domain = domain;
};

TWebObject.prototype = new TObject();
TWebObject.prototype.constructor = TWebObject;

TWebObject.prototype.getDomain = function() {
    return this.domain;
};

TWebObject.prototype.setOrigin = function(value) {
    this.origin = value;
    
    return this;
};

TWebObject.prototype.getOrigin = function() {
    return this.origin;
};


TWebObject.prototype.setToken = function(value) {
    this.token = value;
    
    return this;
};

TWebObject.prototype.getToken = function() {
    return this.token;
};

TWebObject.prototype.getPath = function(url, domain) {
    this.url = new TUrl(url, domain, this.isSSL);
    return this.url.toString();
};

TWebObject.prototype.getUrl = function() {
    return this.url;
};

TWebObject.prototype.getJSON = function(
    url, // Url du webService
    postData, // Tableau JSON des donn�es � poster au webserice
    callBack // fonction qui g�re le retour du webservice
) {
    //$("body").toggleClass('onLoad');
//        spinner.spin();
    postData.token = TRegistry.getToken();
    this.origin = TRegistry.getOrigin();
    
    var urls = this.getPath(url, this.domain);
    $.ajax({
        type: 'POST',
        url: urls,
        data: postData,
        dataType: 'json',
        async: true
    }).done(function(data, textStatus, xhr) {
        try 
        {
            if(data.error !== undefined) {
                debugLog('Error : ' + data.error);
            } else {
                TRegistry.setToken(data.token);
                TRegistry.setOrigin(xhr.getResponseHeader('origin'));
                if($.isFunction(callBack)) {
                    callBack.call(this, data, textStatus, xhr);
                }
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
    postData.token = TRegistry.getToken();
    this.origin = TRegistry.getOrigin();
    var urls = this.getPath(url, this.domain);

    $.ajax({
        type: 'POST',
        url: urls + "&callback=?", // retour en JSONP
        data: postData,
        dataType: 'json',
        async: true
    }).done(function(data, textStatus, xhr) {
        try {
            TRegistry.setToken(data.token);
            TRegistry.setOrigin(xhr.getResponseHeader('origin'));

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

TWebObject.prototype.getScript = function (url, callback) {
    var urls = this.getPath(url, this.domain);

    $.getScript(urls)
    .done(function( script, textStatus ) {
        if(typeof callback === 'function') {
            callback.call(this, script, textStatus);
        }
    })
    .fail(function( jqxhr, settings, exception ) {
        debugLog("Satus : " + jqxhr.status + "\r\n" +
            "Options : " + settings + "\r\n" +
            "Message : " + exception);
    });       
}

TWebObject.getCSS = function(attributes) {
    // setting default attributes
    if(typeof attributes === "string") {
        var href = attributes;
        if(this.origin !== undefined) {
            href = this.origin + '/' + href;
        }
        
        attributes = {
            href: href
        };
    }
    if(!attributes.rel) {
        attributes.rel = "stylesheet"
    }
    // appending the stylesheet
    // no jQuery stuff here, just plain dom manipulations
    var styleSheet = document.createElement("link");
    for(var key in attributes) {
        styleSheet.setAttribute(key, attributes[key]);
    }
    var head = document.getElementsByTagName("head")[0];
        head.appendChild(styleSheet);
};