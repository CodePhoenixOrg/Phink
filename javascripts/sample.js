var sample = null;
Phink.DOM.ready(function () {

    sample = TWebApplication.create('code-phoenix.org', true);
    sample.main = sample.createView('main');

    var sampleMain = sample.createController(sample.main, 'sample.main')
    .actions({
        goHome : function () {
            sampleMain.getSimpleView('master.html', function(data) {
                $(document.body).html(data.view);
                sampleMain.getSimpleView('sample.html', function(data) {
                    $('#homeContent').html(data.view);
                });
            });        
        }
    })
    .onload(function () {
        sampleMain = this;
        this.goHome();
    });
});
