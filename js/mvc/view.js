/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//
var TView = function(name) {
    TWebObject.call(this);
    
    this.setOrigin(TRegistry.item(name).origin);
    
    this.view = null;
    this.token = '';
    this.name = name;
};

TView.prototype = new TWebObject();
TView.prototype.constructor = TView;

TView.create = function(name) {
    return new TView(name);
};

