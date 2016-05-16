/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var TController = function(view, name) {
    TWebObject.call(this);

    console.log('INSIDE ' + name + ' CREATE');
    console.log(view);
    this.domain = (view !== undefined) ? view.getDomain() : '';
    
    this.hasView = true;
    
    if(view instanceof TView) {
        this.parent = view;
    } else if(typeof view === 'Object') {
        throw new Error('Not a valid view');
    } else {
        this.hasView = false;
    }

    this.setName(name);
    
};

TController.prototype = new TWebObject();
TController.prototype.constructor = TController;

TController.create = function(parent, name) {
    if (name === undefined) {
        name = 'ctrl' + Date.now();
    }
    return new TController(parent, name);
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
    this.parent.requestPage(pageName, callback);
};

TController.prototype.getPartialView = function (pageName, action, attach, postData, callback) {
    this.parent.requestPart(pageName, action, attach, postData, callback);
};

TController.prototype.parseViewResponse = function (pageName, callback) {
    this.parent.parseResponse(pageName, callback);
};

TController.prototype.attachWindow = function (pageName, anchor) {
    this.parent.attachWindow(pageName, anchor);
};

TController.prototype.attachView = function (pageName, anchor) {
    this.parent.attachView(pageName, anchor);
};
    
TController.prototype.attachIframe = function(id, src, anchor) {
    this.parent.attachIframe(id, src, anchor);
};
