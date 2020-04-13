Phink.DOM.ready(function () {

    con.createView('console_window');

    var conWindow = con.createController('console_window', 'console_window')
        .actions({
            showToken: function () {
                var token = Phink.Registry.item(home.name).token;

                home.getPartialView('token.html', 'showToken', '#token', {
                    'token': token
                }, function (data) {
                    $("#tokenLink").on("click", function () {
                        home.showToken();
                    });

                });
                return false;
            },
            clearLogs: function () {
                this.getJSON('admin/console/', {
                    "action": 'clearLogs'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            },
            deleteRuntime: function () {
                this.getJSON('admin/console/', {
                    "action": 'clearRuntime'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            },
            displayDebugLog: function () {
                this.getJSON('admin/console/', {
                    "action": 'displayDebugLog'
                }, function (data) {
                    document.querySelector("#result").innerHTML = '<pre>' + data.result + '</pre>';
                });
            },
            displayPhpErrorLog: function () {
                this.getJSON('admin/console/', {
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