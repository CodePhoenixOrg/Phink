Phink.DOM.ready(function () {

    qbe.createView('qbe_window');

    var qbeWindow = qbe.createController('qbe_window', 'qbe_window')
        .actions({
            clearLogs: function () {
                this.getJSON('console', {
                    "action": 'clearLogs'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            }
            , getData: function (count, index, anchor) {

                qbeWindow.getJSON('grid-result.html'
                    , {
                        'action': "getData"
                        , 'pagecount': count
                        , 'pagenum': index
                        , 'query': decodeURIComponent()
                    }
                    , function (data) {
                        Phink.Web.UI.Table.create().bind('#grid', data.grid, function () {
                            $(anchor).html(index);
                        });
    
                    }
                );
    
                return false;
            }
        })
        .onload(function () {
            qbeWindow = this;
            document.querySelector('#rlog').onclick = function () {
                qbeWindow.clearLogs();
            }
        });
});
