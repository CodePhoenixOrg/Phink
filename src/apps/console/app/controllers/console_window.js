Phink.DOM.ready(function () {

    con.createView('console_window');

    var conWindow = con.createController('console_window', 'console_window')
        .actions({
            clearLogs: function () {
                this.getJSON('console', {
                    "action": 'clearLogs'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            }
            , deleteRuntime: function () {
                this.getJSON('console', {
                    "action": 'clearRuntime'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            }
            , displayDebugLog: function () {
                this.getJSON('console', {
                    "action": 'displayDebugLog'
                }, function (data) {
                    document.querySelector("#result").innerHTML = '<pre>' + data.result + '</pre>';
                });
            }
            , displayPhpErrorLog: function () {
                this.getJSON('console', {
                    "action": 'displayPhpErrorLog'
                }, function (data) {
                    document.querySelector("#result").innerHTML = '<pre>' + data.result + '</pre>';
                });
            }
        })
        .onload(function () {
            conWindow = this;
            document.querySelector('#rlog').onclick = function () {
                conWindow.clearLogs();
            }
            document.querySelector('#delrun').onclick = function () {
                conWindow.deleteRuntime();
            }
            document.querySelector('#debug').onclick = function () {
                conWindow.displayDebugLog();
            }
            document.querySelector('#phperr').onclick = function () {
                conWindow.displayPhpErrorLog();
            }
        });
});
