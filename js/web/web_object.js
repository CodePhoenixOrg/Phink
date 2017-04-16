var Phink = Phink || {}

Phink.Web = Phink.Web || {}

Phink.Web.Object = function(domain, isSSL) {
    Phink.Object.call(this);
    
    this.isSSL = isSSL;
    this.origin = '';
    this.url = {};
    this.token = '';
    this.domain = domain;
};

Phink.Web.Object.prototype = new Phink.Object();
Phink.Web.Object.prototype.constructor = Phink.Web.Object;

Phink.Web.Object.prototype.getDomain = function() {
    return this.domain;
};

Phink.Web.Object.prototype.setOrigin = function(value) {
    this.origin = value;
    
    return this;
};

Phink.Web.Object.prototype.getOrigin = function() {
    return this.origin;
};


Phink.Web.Object.prototype.setToken = function(value) {
    this.token = value;
    
    return this;
};

Phink.Web.Object.prototype.getToken = function() {
    return this.token;
};

Phink.Web.Object.prototype.getPath = function(url, domain) {
    this.url = new Phink.Url(url, domain, this.isSSL);
    return this.url.toString();
};

Phink.Web.Object.prototype.getUrl = function() {
    return this.url;
};

Phink.Web.Object.prototype.getJSON = function(
    url, // Url du webService
    postData, // Tableau JSON des donn�es � poster au webserice
    callback // fonction qui g�re le retour du webservice
) {
    //$("body").toggleClass('onLoad');
//        spinner.spin();
    postData.token = Phink.Registry.getToken();
    this.origin = Phink.Registry.getOrigin();
    
    var urls = this.getPath(url, this.domain);
    var xhr = new XMLHttpRequest()

    var params = '';
    for(var key in postData) {
        if (postData.hasOwnProperty(key)) {
            params += '&' + encodeURI(key + '=' + postData[key]);
        }
    }
    params = params.substring(1);

    xhr.open('POST', urls);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader("Accept", "application/json, text/javascript, */*; q=0.01");
    xhr.onload = function() {
        if(typeof callback === 'function') {
            if (xhr.status === 200 || xhr.status === 202) {
                var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : [];

                try 
                {
                    if(data.error !== undefined) {
                        debugLog('Error : ' + data.error);
                    } else {
                        Phink.Registry.setToken(data.token);
                        Phink.Registry.setOrigin(xhr.getResponseHeader('origin'));
                        callback.call(this, data, xhr.statusText, xhr);
                    }
                }
                catch(e)
                {
                    debugLog(e);
                }
            }
        }
    }
    xhr.send(params);
};

Phink.Web.Object.prototype.getJSONP = function(url, postData, callBack) {
    postData.token = Phink.Registry.getToken();
    this.origin = Phink.Registry.getOrigin();
    var urls = this.getPath(url, this.domain);

    $.ajax({
        type: 'POST',
        url: urls + "&callback=?", // retour en JSONP
        data: postData,
        dataType: 'json',
        async: true
    }).done(function(data, textStatus, xhr) {
        try {
            Phink.Registry.setToken(data.token);
            Phink.Registry.setOrigin(xhr.getResponseHeader('origin'));

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

Phink.Web.Object.prototype.getScript = function (url, callback) {
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

Phink.Web.Object.getCSS = function(attributes) {
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