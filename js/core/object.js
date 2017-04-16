var Phink = Phink || {}

Phink.Object = function() {
    this.id = '';
    this.name = '';
    this.parent = null;
    
};

Phink.Object.prototype.setId = function(value) {
    this.id = value;
    
    return this;
};

Phink.Object.prototype.getId = function() {
    return this.id;
};

Phink.Object.prototype.setName = function(value) {
    this.name = value;
    
    return this;
};

Phink.Object.prototype.getName = function() {
    return this.name;
};

//TObject.prototype.setParent = function(value) {
//    this.parent = value;
//    
//    return this;
//};

Phink.Object.prototype.getParent = function() {
    return this.parent;
};