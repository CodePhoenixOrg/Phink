/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function include(file) {
    var myScript =  document.createElement("script");
    myScript.src = file;
    myScript.type = "text/javascript";
    document.body.appendChild(myScript);
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var TRegistry = (function() {
    
    var F = function() {
        this.registry = {};
    }

    F.prototype.write = function(item, key, value) {

        if (this.registry[item] === undefined) {
            this.registry[item] = {};
        }
        this.registry[item][key] = value;

    }

    F.prototype.read = function(item, key, ifNull) {
        var result = null;

        if (this.registry[item] !== undefined) {
            result = (this.registry[item][key] !== undefined) ? this.registry[item][key] : ((ifNull !== undefined) ? ifNull : null);
        }

        return result;
    }

    F.prototype.item = function(item) {
        if(item === '' || item === undefined) return null;

        if(this.registry[item] !== undefined) {
            return this.registry[item];
        } else {
            this.registry[item] = {};
            return this.registry[item];
        }
    }

    F.prototype.clear = function() {
        this.registry = {};
    }
    
    return new F();
})();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TWebApplication = function() {
    
};

TWebApplication.create = function() {

}/* 
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
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TController = function() {
    TWebObject.call(this);
    
    this.view = null;
    this.token = '';
};

TController.prototype = new TWebObject();
TController.prototype.constructor = TController;

TController.prototype.render = function () {

    if(typeof this.oninit === 'function') {
        this.oninit();
    }   
    if(typeof this.onload === 'function') {
        this.onload();
    }
};

TController.prototype.actions = function (actions) {

    for(var key in actions) {
        this[key] = actions[key];
    }

    this.render();

    return this;
};

TController.prototype.getView = function (pageName, callback) {

    $.ajax({
        type: 'POST',
        url: (this.origin !== undefined) ? this.origin + pageName : pageName,
        data: {"action" : 'getViewHtml', "token" : this.token},
        dataType: 'json',
        async: true,
        headers: {
            "Accept" : "application/json, text/javascript, request/view, */*; q=0.01"
//            ,   "X-Token:" : myToken
        }
    }).done(function(data, textStatus, xhr) {
        try {
            this.origin = xhr.getResponseHeader('origin');
            var url = TWebObject.parseUrl(pageName);
            TRegistry.item(url.page).origin = this.origin;

            this.token = data.token;

            var l = data.scripts.length;
            for(i = 0; i < l; i++) {
                $.getScript(data.scripts[i]);
            }

            data.view = base64_decode(data.view);
            if($.isFunction(callback)) {
                callback.call(this, data);
            } else {
                $("#mainContent").html(data.view);

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

TController.prototype.getPartialView = function (pageName, action, attach, postData, callBack) {

    if(postData === undefined) {
        postData = {};
    }

    postData.action = action;
    postData.token = this.token;

    $.ajax({
        type: 'POST',
        url: (this.origin !== undefined) ? this.origin + pageName : pageName,
        data: postData,
        dataType: 'json',
        async: true,
        headers: {
            "Accept" : "application/json, text/javascript, request/partialview, */*; q=0.01"
//            ,   "X-Token:" : myToken
        }
    }).done(function(data, textStatus, xhr) {
        try 
        {
            this.token = data.token;

            this.origin = xhr.getResponseHeader('origin');
            var url = TWebObject.parseUrl(pageName);
            TRegistry.item(url.page).origin = this.origin;

            var l = data.scripts.length;
            for(i = 0; i < l; i++) {
                $.getScript(data.scripts[i]);
            }

            if($.isFunction(callBack)) {
                callBack.call(this, data);
            }

            var html = base64_decode(data.view);
            $(attach).html(html);
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
