/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TController = function(name) {
    TWebObject.call(this);

    this.view = null;
    this.setName(name);

};

TController.prototype = new TWebObject();
TController.prototype.constructor = TController;

TController.create = function(name) {
    if (name === undefined) {
        name = 'ctrl' + Date.now();
    }
    return new TController(name);
};

TController.prototype.oninit = function (callback) {

    if(typeof callback === 'function') {
        callback.call(this);
    }
    
    return this;
};

TController.prototype.onload = function (callback) {

    if(typeof callback === 'function') {
        callback.call(this);
    }
    
    return this;
};

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
    console.log(pageName);
    
    var the = this;
    var token = TRegistry.getToken();
    $.ajax({
        type: 'POST',
        url: (this.origin !== 'undefined/' && this.origin !== undefined) ? this.origin + pageName : pageName,
        data: {"action" : 'getViewHtml', "token" : token},
        dataType: 'json',
        async: true,
        headers: {
            "Accept" : "application/json, text/javascript, request/view, */*; q=0.01"
//            ,   "X-Token:" : myToken
        }
    }).done(function(data, textStatus, xhr) {
        try {
//            var url = TWebObject.parseUrl(pageName);
//            TRegistry.item(the.name).origin = xhr.getResponseHeader('origin');
            TRegistry.setOrigin(xhr.getResponseHeader('origin'));
            TRegistry.setToken(data.token);

            var l = data.scripts.length;
            for(i = 0; i < l; i++) {
                $.getScript(data.scripts[i]);
            }

            data.view = base64_decode(data.view);
            if(typeof callback === 'function') {
                callback.call(this, data);
            } else {
                $(document.body).html(data.view);

            }
            
        }
        catch(e) {
            $.jPhoenix.debugLog(e);
        }
    }).fail(function(xhr, options, message) {
        $.jPhoenix.debugLog("Satus : " + xhr.status + "\r\n" +
            "Options : " + options + "\r\n" +
            "Message : " + message);
    });
};

TController.prototype.getPartialView = function (pageName, action, attach, postData, callBack) {

    postData = postData || {};
    
    console.log('token1::' + this.token);
    console.log('name::' + this.name);

    var token = TRegistry.getToken();
    console.log('token2::' + token);
    
    postData.action = action;
    postData.token = token;

    var the = this;
    $.ajax({
        type: 'POST',
        url: (this.origin !== undefined) ? this.origin + '/' + pageName : pageName,
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
            TRegistry.setToken(data.token);

            var url = TWebObject.parseUrl(pageName);
//            TRegistry.item(the.name).origin = xhr.getResponseHeader('origin');
            TRegistry.setOrigin(xhr.getResponseHeader('origin'));

            var l = data.scripts.length;
            for(i = 0; i < l; i++) {
                $.getScript(data.scripts[i]);
            }

            var html = base64_decode(data.view);
            $(attach).html(html);
            
            if(typeof callBack === 'function') {
                callBack.call(this, data);
            }            
        }
        catch(e)
        {
            $.jPhoenix.debugLog(e);
        }
    }).fail(function(xhr, options, message) {
        $.jPhoenix.debugLog("Satus : " + xhr.status + "\r\n" +
                "Options : " + options + "\r\n" +
                "Message : " + message);
    });
};

TController.prototype.attachWindow = function (pageName, anchor) {
    this.getView(pageName, function(data) {
        if(anchor !== undefined) {
            $(anchor).html(data.view);
        } else {
            $(document.body).html(data.view);
        }
    });
};

TController.prototype.attachView = function (pageName, anchor) {
    this.getView(pageName, function(data) {
        $(anchor).html(data.view);
    });
};


    
TController.prototype.attachIframe = function(id, src, anchor) {
//    var iframe = document.createElement('iframe');
//    iframe.frameBorder = 0;
//    iframe.width = "100%";
//    iframe.height = "100%";
//    iframe.id = id;
//    iframe.setAttribute("src", src);
//    document.getElementById(anchor).appendChild(iframe);

    $(anchor).html('');
    $('<iframe>', {
        src: src,
        id:  id,
        frameborder: 0,
        scrolling: 'no'
    }).appendTo(anchor);

};