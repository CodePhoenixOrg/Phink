/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var TObject = function() {
    this.id = '';
    this.name = '';
    
};

TObject.prototype.setId = function(value) {
    this.id = value;
    
    return this;
};

TObject.prototype.getId = function() {
    return this.id;
};

TObject.prototype.setName = function(value) {
    this.name = value;
    
    return this;
};

TObject.prototype.getName = function() {
    return this.name;
};