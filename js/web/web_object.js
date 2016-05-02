/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TWebObject = function() {
    this.origin = '';
    this.url = {};
    this.token = '';
    this.name = '';
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

TWebObject.parseUrl = function (url) {

    console.log('url : ' + url);
    var result = {};

    var protocol = (url.search('://') > -1) ? url.substring(0, url.search('://')) : null;
    var page = window.location.pathname;
    var domain = url;
    var port = '80';
    
    if(protocol === null) {
        page = url;

        result.protocol = window.location.protocol;
        result.domain = window.location.hostname;
        result.port = window.location.port;
        //url = window.location.href.substring(0, window.location.href.search('/'));
    } else {
        
        url = url.replace(protocol + '://', '');
        var domainLimit = url.search('/');
        
        if(domainLimit > -1) {
            domain = url.substring(0, domainLimit);
            url = url.replace(domain, '');
        }

        if(domain.search(':') > -1) {
            port = domain.substring(domain.search(':'));
            url = url.replace(':' + port, '');
        }

        if(domain.search('localhost') > -1) {
            domain = 'localhost';
            url = url.replace(domain, '');
        }
        
        page = url;
        if(page.substring(0,1) === '/') {
            page = page.substring(1);
        }

        result.protocol = protocol;
        result.domain = domain;
        result.port = port;
        
    }

    var queryString = '';
    if(page.search(/\?/) > -1) {
        queryString = page.substring(page.search(/\?/));
    }
    
    result.page = page; //url.replace('.html', '');
    result.queryString = queryString;

    console.log(result);
    this.url = result;

    return result;
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
    postData.token = this.token;
    
    if(this.origin !== undefined) {
        var url = TWebObject.parseUrl(url);
        url = this.origin + '/' + url.page;
    }

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
            TRegistry.item(url.page).token = data.token;
            
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


/*
* jQuery getCSS Plugin
* Copyright 2013, intesso
* MIT license.
*
* cross browser function to dynamically load an external css file.
* see: [github page](http://intesso.github.com/jquery-getCSS/)
*
*/

/*
arguments: attributes
attributes can be a string: then it goes directly inside the href attribute.
e.g.: $.getCSS("fresh.css")

attributes can also be an objcet.
e.g.: $.getCSS({href:"cool.css", media:"print"})
or: $.getCSS({href:"/styles/forest.css", media:"screen"})
*/
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

