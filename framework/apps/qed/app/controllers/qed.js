var qedApp = null;
var qed = null;
Phink.DOM.ready(function () {

    qedApp = Phink.Web.Application.create(qedHost, qedName);
    qedApp.createView('qed');

    qed = qedApp.createController('qed', 'main')
        .actions({
            themeIbmPc: function () {
                this.getJSON('admin/qed/', {
                    "action": 'setTheme',
                    "theme": 'ibm_pc'
                }, function (data) {
                    qed.applyTheme(data);
                });
            },
            themeAmstradCpc: function () {
                this.getJSON('admin/qed/', {
                    "action": 'setTheme',
                    "theme": 'amstrad_cpc'
                }, function (data) {
                    qed.applyTheme(data);
                });
            },
            themeSolaris: function () {
                this.getJSON('admin/qed/', {
                    "action": 'setTheme',
                    "theme": 'solaris'
                }, function (data) {
                    qed.applyTheme(data);
                });
            },
            applyTheme: function (data) {
                document.querySelector("#result").innerHTML = data.theme.name;
                document.querySelector(':root').style.setProperty('--back-color', data.theme.backColor);
                document.querySelector(':root').style.setProperty('--fore-color', data.theme.foreColor);
            },
            clearLogs: function () {
                this.getJSON('admin/qed/', {
                    "action": 'clearLogs'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            },
            testQuery: function (sql) {
                this.getJSON('admin/qed/', {
                    "action": 'testQuery',
                    "sql": sql
                }, function (data) {
                    document.querySelector("#recordset pre").innerHTML = data.result;
                });
            },
            sendQuery: function () {
                var sql = document.querySelector("#query").value;
                sql = encodeURIComponent(sql);
                var count = 15;
                var index = 1;
                var anchor = '#grid';

                qed.getJSON('admin/qed/grid/', {
                    'action': "getData",
                    'pagecount': count,
                    'pagenum': index,
                    'query': sql
                }, function (data) {
                    Phink.Web.UI.Table.create().bind('#grid', data.grid, function () {
                        //document.querySelector('#grid').innerHTML(index);
                    });

                });
            },
            getData: function (count, index, anchor) {
                var sql = document.querySelector("#query").value;
                sql = encodeURIComponent(sql);

                this.getJSON('admin/qed/grid/', {
                    'action': "getData",
                    'pagecount': count,
                    'pagenum': index,
                    'query': sql
                    //, 'token'
                }, function (data) {
                    document.querySelector(anchor).innerHTML = index;
                    Phink.Web.UI.Table.create().bind('#grid', data.grid, function () {
                        //document.querySelector('#grid').innerHTML(index);
                    });
                });

                return false;
            }
        })
        .onload(function () {
            qed = this;
            document.querySelector('#ibm-pcTheme').onclick = function () {
                qed.themeIbmPc();
            }
            document.querySelector('#amstrad-cpcTheme').onclick = function () {
                qed.themeAmstradCpc();
            }
            document.querySelector('#solarisTheme').onclick = function () {
                qed.themeSolaris();
            }
            document.querySelector('#rlog').onclick = function () {
                qed.clearLogs();
            }
            document.querySelector('#sendQuery').onclick = function () {
                qed.sendQuery();
            }

        });

})
