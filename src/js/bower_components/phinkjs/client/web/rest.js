var Phink = Phink || {}

Phink.Web = Phink.Web || {}

Phink.Web.Rest = (function() {
    var F = function() {

    }

    /**
     * Performs a HEAD request and return the result to a callback function
     * 
     * @param {string} url
     * @param {function} callback
     * @returns JSON stream
     */
    F.prototype.head = function(url, callback) {
        var xhr = new XMLHttpRequest()
        xhr.open('HEAD', url)
        xhr.onload = function() {
            if(typeof callback === 'function') {
                if (xhr.status === 200) {
                    var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : []
                    callback.call(this, data)
                } else {
                    callback.call(this, xhr.status)
                }
            }
        }
        xhr.send()
    }

    /**
     * Performs a GET request and return the result to a callback function
     * 
     * @param {string} url
     * @param {function} callback
     * @returns JSON stream
     */
     F.prototype.get = function(url, callback) {
        var xhr = new XMLHttpRequest()
        xhr.open('GET', url)
        xhr.onload = function() {
            if(typeof callback === 'function') {
                if (xhr.status === 200) {
                    var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : []
                    callback.call(this, data)
                } else {
                    callback.call(this, xhr.status)
                }
            }
        }
        xhr.send()
    }

    /**
     * Performs a POST request and return the result to a callback function
     * 
     * @param {string} url
     * @param {array} data
     * @param {function} callback
     * @returns JSON stream on callback
     */
     F.prototype.post = function(url, data, callback) {

        var xhr = new XMLHttpRequest()

        var params = '';
        for(var key in data) {
            if (data.hasOwnProperty(key)) {
                params += '&' + encodeURI(key + '=' + data[key])
            }
        }
        params = params.substring(1);

        xhr.open('POST', url)
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.onload = function() {
            if(typeof callback === 'function') {
                if (xhr.status === 200) {
                    var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : []
                    callback.call(this, data)
                } else {
                    callback.call(this, xhr.status)
                }
            }
        }
        xhr.send(params);
    }

    /**
     * Performs a PATCH request and return the result to a callback function
     * 
     * @param {string} url
     * @param {array} data
     * @param {function} callback
     * @returns JSON stream on callback
     */
    F.prototype.patch = function(url, data, callback) {

        var xhr = new XMLHttpRequest()

        var params = '';
        for(var key in data) {
            if (data.hasOwnProperty(key)) {
                params += '&' + encodeURI(key + '=' + data[key])
            }
        }
        params = params.substring(1);

        xhr.open('PATCH', url)
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.onload = function() {
            if(typeof callback === 'function') {
                if (xhr.status === 200) {
                    var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : []
                    callback.call(this, data)
                } else {
                    callback.call(this, xhr.status)
                }
            }
        }
        xhr.send(params);
    }

    /**
     * Performs a PUT request and return the result to a callback function
     * 
     * @param {string} url
     * @param {array} data
     * @param {function} callback
     * @returns JSON stream on callback
     */
    F.prototype.put = function(url, data, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('PUT', url);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if(typeof callback === 'function') {
                if (xhr.status === 200) {
                    var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : []
                    callback.call(this, data)
                } else {
                    callback.call(this, xhr.status)
                }
            }
        };
        xhr.send(JSON.stringify(data));    
    }

    /**
     * Performs a DELETE request and return the result to a callback function
     * 
     * @param {string} url
     * @param {function} callback
     * @returns JSON stream on callback
     */
    F.prototype.delete = function(url, callback) {
        var xhr = new XMLHttpRequest()
        xhr.open('DELETE', url)
        xhr.onload = function() {
            if(typeof callback === 'function') {
                if (xhr.status === 200) {
                    var data = (xhr.responseText !== '') ? JSON.parse(xhr.responseText) : []
                    callback.call(this, data)
                } else {
                    callback.call(this, xhr.status)
                }
            }
        }
        xhr.send()
    }
    
    return new F()
})()

