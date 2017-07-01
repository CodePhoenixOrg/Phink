var Phink = Phink || {}

Phink.Registry = (function () {
    
    var F = function() {
        this.registry = {};
    }

    F.prototype.write = function(item, key, value) {

        if (this.registry[item] === undefined) {
            this.registry[item] = {};
        }
        this.registry[item][key] = value;

    }

    F.prototype.read = function(item, key, defaultValue) {
        var result = null;

        if (this.registry[item] !== undefined) {
            result = (this.registry[item][key] !== undefined) ? this.registry[item][key] : ((defaultValue !== undefined) ? defaultValue : null);
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
    
    F.prototype.items = function() {
        return this.registry;
    }

    F.prototype.clear = function() {
        this.registry = {};
    }
    
    F.prototype.setToken = function(value) {
        this.registry['token'] = value;
    
        return this;
    };

    F.prototype.getToken = function() {
        return this.registry['token'];
    };

    F.prototype.setOrigin = function(value) {
        this.registry['origin'] = value;
    
        return this;
    };

    F.prototype.getOrigin = function() {
        return this.registry['origin'];
    };

    return new F();
})();