Phink.DOM.ready(function () {

    qed.createView('qed_window');

    var qedWindow = qed.createController('qed_window', 'qed_window')
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
            qedWindow = this;
            document.querySelector('#rlog').onclick = function () {
                qedWindow.clearLogs();
            }
        });
});
