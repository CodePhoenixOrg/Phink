var con = null;
var conHost = window.location.hostname;
Phink.DOM.ready(function () {

    con = Phink.Web.Application.create(conHost);
    con.main = con.createView('main');

    var conMain = con.createController(con.main, 'con.main')
        .actions({
            themeIbmPc: function () {
                document.querySelector('html').setAttribute('class', 'ibm-pc');
            }
            , themeAmstradCpc: function () {
                document.querySelector('html').setAttribute('class', 'amstrad-cpc');
            }
            , themeSolaris: function () {
                document.querySelector('html').setAttribute('class', 'solaris');
            }
            , clearLogs: function () {
                this.getJSON('rlog', {
                    "action": 'clearLogs'
                } , function (data) {
                    document.querySelector("#result").innerHtml = data;
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
        });
});
