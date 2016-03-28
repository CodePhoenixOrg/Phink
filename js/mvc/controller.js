/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TController = function(name) {
    TWebObject.call(this);
    
    this.setOrigin(TRegistry.item(name).origin);
    
    this.view = null;
    this.token = '';
    this.name = name;
};

TController.prototype = new TWebObject();
TController.prototype.constructor = TController;

TController.create = function(name) {
    return new TController(name);
}

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

    $.ajax({
        type: 'POST',
        url: (this.origin !== 'undefined/' && this.origin !== undefined) ? this.origin + pageName : pageName,
        data: {"action" : 'getViewHtml', "token" : this.token},
        dataType: 'json',
        async: true,
        headers: {
            "Accept" : "application/json, text/javascript, request/view, */*; q=0.01"
//            ,   "X-Token:" : myToken
        }
    }).done(function(data, textStatus, xhr) {
        try {
            var url = TWebObject.parseUrl(pageName);
            TRegistry.item(url.page).origin = xhr.getResponseHeader('origin');

            this.token = data.token;

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

    if(postData === undefined || postData === null) {
        postData = {};
    }

    postData.action = action;
    postData.token = this.token;

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
            this.token = data.token;

            var url = TWebObject.parseUrl(pageName);
            TRegistry.item(url.page).origin = xhr.getResponseHeader('origin');

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
    var myToken = this.token;
    this.getJSON('' + pageName, {"action" : 'getViewHtml', "token" : myToken}, function(data) {
        try {
            this.token = data.token;

            var l = data.scripts.length;
            for(i = 0; i < l; i++) {
                $.getScript(data.scripts[i]);
            }

            var html = base64_decode(data.view);
            $(anchor).html(html);                
        }
        catch(e) {
            debugLog(e);
        }
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

}
