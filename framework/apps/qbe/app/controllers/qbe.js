var qbeApp = null;
var qbe = null;
Phink.DOM.ready(function () {

    qbeApp = Phink.Web.Application.create(qbeHost, qbeName);
    qbeApp.createView('qbe');

    qbe = qbeApp.createController('qbe', 'main')
        .actions({
            themeIbmPc: function () {
                this.getJSON('admin/console/', {
                    "action": 'setTheme',
                    "theme": 'ibm_pc'
                }, function (data) {
                    qbe.applyTheme(data);
                });
            },
            themeAmstradCpc: function () {
                this.getJSON('admin/console/', {
                    "action": 'setTheme',
                    "theme": 'amstrad_cpc'
                }, function (data) {
                    qbe.applyTheme(data);
                });
            },
            themeSolaris: function () {
                this.getJSON('admin/console/', {
                    "action": 'setTheme',
                    "theme": 'solaris'
                }, function (data) {
                    qbe.applyTheme(data);
                });
            },
            applyTheme: function (data) {
                document.querySelector("#result").innerHTML = data.theme.name;
                document.querySelector(':root').style.setProperty('--back-color', data.theme.backColor);
                document.querySelector(':root').style.setProperty('--fore-color', data.theme.foreColor);
            },
            clearLogs: function () {
                this.getJSON('admin/console/', {
                    "action": 'clearLogs'
                }, function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            },
            testQuery: function (sql) {
                this.getJSON('admin/qbe/', {
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

                qbe.getJSON('admin/qbe/grid/', {
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

                this.getJSON('admin/qbe/grid/', {
                    'action': "getData",
                    'pagecount': count,
                    'pagenum': index,
                    'query': sql
                    //, 'token'
                }, function (data) {
                    Phink.Web.UI.Table.create().bind('#grid', data.grid, function () {
                        //document.querySelector('#grid').innerHTML(index);
                    });
                });

                return false;
            }
        })
        .onload(function () {
            qbe = this;
            document.querySelector('#ibm-pcTheme').onclick = function () {
                qbe.themeIbmPc();
            }
            document.querySelector('#amstrad-cpcTheme').onclick = function () {
                qbe.themeAmstradCpc();
            }
            document.querySelector('#solarisTheme').onclick = function () {
                qbe.themeSolaris();
            }
            document.querySelector('#rlog').onclick = function () {
                qbe.clearLogs();
            }
            document.querySelector('#sendQuery').onclick = function () {
                qbe.sendQuery();
            }

        });

})
