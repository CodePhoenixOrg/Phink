var Phink = Phink || {}

Phink.Web = Phink.Web || {}

Phink.Web.Application = function (domain, name, isSSL) {
    Phink.Web.Object.call(this, domain, isSSL);
    
    this.id = 'app' + Date.now();
    if(name === undefined) {
        name = this.id;
    }
    
    this.name = name;
    //this.domain = domain;
    this.viewCollection = [];
    this.controllerCollection = [];
  
};

Phink.Web.Application.prototype = new Phink.Web.Object();
Phink.Web.Application.prototype.constructor = Phink.Web.Application;

Phink.Web.Application.create = function(domain, name, isSSL) {
    return new Phink.Web.Application(domain, name, isSSL);
};

Phink.Web.Application.prototype.includeView = function(name) {
    include('app/controllers/' + name + '/' + name + '.js');
    var newView = Phink.MVC.View.create(this, name);
    this.addView(newView);
    
    return newView;
};

Phink.Web.Application.prototype.createView = function(name) {
    var newView = Phink.MVC.View.create(this, name);
    this.addView(newView);
    
    return newView;
};


Phink.Web.Application.prototype.createController = function(view, name) {
    var newCtrl = Phink.MVC.Controller.create(view, name);
    this.addController(newCtrl);
    
    return newCtrl;
};

Phink.Web.Application.prototype.getViewByName = function(viewName) {
    var result = null;
    
    for(var name in this.viewCollection) {
        if(this.viewCollection[name] !== undefined) {
            result = this.viewCollection[name];
            break;
        }
    }
    
    return result;
}

Phink.Web.Application.prototype.addView = function(view) {
    if(view === undefined) return null;

    if(!(view instanceof Phink.MVC.View)) {
        throw new Error('This is not a view');
    } else {
        this.viewCollection[view.getName()] = view;
    }

};

Phink.Web.Application.prototype.addController = function(controller) {
    if(controller === undefined) return null;

    if(!(controller instanceof Phink.MVC.Controller)) {
        throw new Error('This is not a controller');
    } else {
        this.controllerCollection.push(controller);
    }

};