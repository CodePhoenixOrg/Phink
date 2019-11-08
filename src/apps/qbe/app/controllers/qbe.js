Phink.DOM.ready(function () {

    qbe = Phink.Web.Application.create(qbeHost, qbeName);
    qbe.createView('main');

    var qbeMain = qbe.createController('main', 'main')
        .actions({
            themeIbmPc: function () {
                this.getJSON('qbe', {
                    "action": 'setTheme', "theme": 'ibm_pc'
                } , function (data) {
                    qbeMain.applyTheme(data);
                });
            }
            , themeAmstradCpc: function () {
                this.getJSON('qbe', {
                    "action": 'setTheme', "theme": 'amstrad_cpc'
                } , function (data) {
                    qbeMain.applyTheme(data);
                });
            }
            , themeSolaris: function () {
                this.getJSON('qbe', {
                    "action": 'setTheme', "theme": 'solaris'
                } , function (data) {
                    qbeMain.applyTheme(data);
                });
            }
            , applyTheme: function(data) {
                document.querySelector("#result").innerHTML = data.theme.name;
                document.querySelector(':root').style.setProperty('--back-color', data.theme.backColor);
                document.querySelector(':root').style.setProperty('--fore-color', data.theme.foreColor);
            }
            , clearLogs: function () {
                this.getJSON('qbe', {
                    "action": 'clearLogs'
                } , function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            }
            , testQuery: function (sql) {
                this.getJSON('qbe', {
                    "action": 'testQuery',
                    "sql": sql
                } , function (data) {
                    document.querySelector("#recordset pre").innerHTML = data.result;
                });
            }
        })
        .onload(function () {
            qbeMain = this;
            document.querySelector('#ibm-pcTheme').onclick = function () {
                qbeMain.themeIbmPc();
            }
            document.querySelector('#amstrad-cpcTheme').onclick = function () {
                qbeMain.themeAmstradCpc();
            }
            document.querySelector('#solarisTheme').onclick = function () {
                qbeMain.themeSolaris();
            }
            document.querySelector('#rlog').onclick = function () {
                qbeMain.clearLogs();
            }
            document.querySelector('#sendQuery').onclick = function () {
                var sql = document.querySelector("#query").value;
                sql = btoa(sql);
                qbeMain.testQuery(sql);
            }

        });
});