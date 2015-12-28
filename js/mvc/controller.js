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
            $.jPhoenix.debugLog(e);
        }
    }).fail(function(xhr, options, message) {
        $.jPhoenix.debugLog("Satus : " + xhr.status + "\r\n" +
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
            $.jPhoenix.debugLog(e);
        }
    }).fail(function(xhr, options, message) {
        $.jPhoenix.debugLog("Satus : " + xhr.status + "\r\n" +
                "Options : " + options + "\r\n" +
                "Message : " + message);
    });
};
