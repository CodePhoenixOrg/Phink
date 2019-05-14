var console = null;
var consoleHost = window.location.hostname;
Phink.DOM.ready(function () {

    console = Phink.Web.Application.create(consoleHost);
    console.main = console.createView('main');

    var consoleMain = console.createController(console.main, 'console.main')
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
    })
    .onload(function () {
        consoleMain = this;
        document.querySelector('#ibm-pcTheme').onclick = function() {
            consoleMain.themeIbmPc();
        }
        document.querySelector('#amstrad-cpcTheme').onclick = function() {
            consoleMain.themeAmstradCpc();
        }
        document.querySelector('#solarisTheme').onclick = function() {
            consoleMain.themeSolaris();
        }
    });
});
