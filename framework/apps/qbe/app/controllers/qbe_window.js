Phink.DOM.ready(function () {

    qbe.createView('qbe_window');

    var qbeWindow = qbe.createController('qbe_window', 'qbe_window')
        .actions({
            clearLogs: function () {
                this.getJSON('console/', {
                    "action": 'clearLogs'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            }
        })
        .onload(function () {
            qbeWindow = this;
            document.querySelector('#rlog').onclick = function () {
                qbeWindow.clearLogs();
            }
        });
});
