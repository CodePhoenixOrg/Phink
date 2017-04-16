var Phink = Phink || {}

Phink.MVC = Phink.MVC || {}

Phink.MVC.Controller = function(view, name) {
    Phink.Web.Object.call(this);
    context = this;
    this.domain = (view !== undefined) ? view.getDomain() : '';
    this.hasView = true;
    
    if(view instanceof Phink.MVC.View) {
        this.parent = view;
    } else if(typeof view === 'Object') {
        throw new Error('Not a valid view');
    } else {
        this.hasView = false;
    }

    this.setName(name);
    
};

Phink.MVC.Controller.prototype = new Phink.Web.Object();
Phink.MVC.Controller.prototype.constructor = Phink.MVC.Controller;

Phink.MVC.Controller.create = function(parent, name) {
    if (name === undefined) {
        name = 'ctrl' + Date.now();
    }
    return new Phink.MVC.Controller(parent, name);
};

Phink.MVC.Controller.prototype.oninit = function (callback) {

    if(typeof callback === 'function') {
        callback.call(this);
    }
    
    return this;
};

Phink.MVC.Controller.prototype.onload = function (callback) {

    var the = this
    Phink.DOM.ready(function() {
        if(typeof callback === 'function') {
            callback.call(the);
        }
    })
    
    return this;
};

Phink.MVC.Controller.prototype.render = function () {

    if(typeof this.oninit === 'function') {
        this.oninit();
    }   
    if(typeof this.onload === 'function') {
        this.onload();
    }
};

Phink.MVC.Controller.prototype.actions = function (actions) {

    for(var key in actions) {
        this[key] = actions[key];
    }

    this.render();

    return this;
};

Phink.MVC.Controller.prototype.route = function (route, callback) {
    
    var routeMatcher = new RegExp(route.replace(/:[^\s/]+/g, '([\\w-]+)'));
    this.parent.requestView(view, action, args, callback);
};

Phink.MVC.Controller.prototype.getSimpleView = function (view, callback) {
    this.parent.requestSimpleView(view, callback);
};

Phink.MVC.Controller.prototype.getView = function (view, action, args, callback) {
    this.parent.requestView(view, action, args, callback);
};

Phink.MVC.Controller.prototype.getPartialView = function (pageName, action, attach, postData, callback) {
    this.parent.requestPart(pageName, action, attach, postData, callback);
};

Phink.MVC.Controller.prototype.parseViewResponse = function (pageName, callback) {
    this.parent.parseResponse(pageName, callback);
};

Phink.MVC.Controller.prototype.attachWindow = function (pageName, anchor) {
    this.parent.attachWindow(pageName, anchor);
};

Phink.MVC.Controller.prototype.attachView = function (pageName, anchor) {
    this.parent.attachView(pageName, anchor);
};
    
Phink.MVC.Controller.prototype.attachIframe = function(id, src, anchor) {
    this.parent.attachIframe(id, src, anchor);
};
