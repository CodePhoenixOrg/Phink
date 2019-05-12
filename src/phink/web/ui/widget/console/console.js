var console = null;
var consoleHost = windows.location.hostname;
Phink.DOM.ready(function () {

    console = Phink.Web.Application.create(consoleHost);
    console.main = console.createView('main');

    var consoleMain = console.createController(console.main, 'console.main')
    .actions({
    })
    .onload(function () {
        consoleMain = this;
    });
});
