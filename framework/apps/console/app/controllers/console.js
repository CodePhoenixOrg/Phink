Phink.DOM.ready(function () {

    con = Phink.Web.Application.create(conHost, conName);
    con.createView('main');

    var conMain = con.createController('main', 'main')
        .actions({
            themeIbmPc: function () {
                this.getJSON('console/', {
                    "action": 'setTheme', "theme": 'ibm_pc'
                } , function (data) {
                    conMain.applyTheme(data);
                });
            }
            , themeAmstradCpc: function () {
                this.getJSON('console/', {
                    "action": 'setTheme', "theme": 'amstrad_cpc'
                } , function (data) {
                    conMain.applyTheme(data);
                });
            }
            , themeSolaris: function () {
                this.getJSON('console/', {
                    "action": 'setTheme', "theme": 'solaris'
                } , function (data) {
                    conMain.applyTheme(data);
                });
            }
            , applyTheme: function(data) {
                document.querySelector("#result").innerHTML = data.theme.name;
                document.querySelector(':root').style.setProperty('--back-color', data.theme.backColor);
                document.querySelector(':root').style.setProperty('--fore-color', data.theme.foreColor);
                console.log(data);
            }
            , clearLogs: function () {
                this.getJSON('console/', {
                    "action": 'clearLogs'
                } , function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            }
            , deleteRuntime: function () {
                Phink.Commands.clearRuntime(function (data) {
                    document.querySelector("#result").innerHTML = data.result;
                });
            }
            , displayDebugLog: function () {
                this.getJSON('console/', {
                    "action": 'displayDebugLog'
                } , function (data) {
                    document.querySelector("#result").innerHTML = '<pre>' + data.result + '</pre>';
                });
            }
            , displayPhpErrorLog: function () {
                this.getJSON('console/', {
                    "action": 'displayPhpErrorLog'
                } , function (data) {
                    document.querySelector("#result").innerHTML = '<pre>' + data.result + '</pre>';
                });
            }
        })
        .onload(function () {
            conMain = this;
            document.querySelector('#ibm-pcTheme').onclick = function () {
                conMain.themeIbmPc();
            }
            document.querySelector('#amstrad-cpcTheme').onclick = function () {
                conMain.themeAmstradCpc();
            }
            document.querySelector('#solarisTheme').onclick = function () {
                conMain.themeSolaris();
            }
            document.querySelector('#rlog').onclick = function () {
                conMain.clearLogs();
            }
            document.querySelector('#delrun').onclick = function () {
                conMain.deleteRuntime();
            }
            document.querySelector('#debug').onclick = function () {
                conMain.displayDebugLog();
            }
            document.querySelector('#phperr').onclick = function () {
                conMain.displayPhpErrorLog();
            }
        });
});
