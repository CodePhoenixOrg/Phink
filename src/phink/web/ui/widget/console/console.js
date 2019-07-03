var con = null;
var conHost = window.location.hostname;
Phink.DOM.ready(function () {

    con = Phink.Web.Application.create(conHost, 'console');
    con.createView('main');

    var conMain = con.createController('main', 'main')
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
                    document.querySelector("#result").innerHTML = data.result;
                });
                // this.getPartialView('rlog', 'clearLogs', "#result", {} , function (data) {
                //     document.querySelector("#result").innerHTML = data.result;
                //     // console.log(data);
                // });
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
