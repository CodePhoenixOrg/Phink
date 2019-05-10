var <% for %>Pager = Phink.MVC.Controller.create()
.oninit(function (){
    this.index = '<% pageNum %>';
    this.count = '<% pageCount %>';
    this.index = (parseInt(this.index)) ? parseInt(this.index) : 1;
    this.count = (parseInt(this.count)) ? parseInt(this.count) : 20;
            
})
.actions({
    fastLeft : function() {
        this.index--;
        this.index = (this.index < 1) ? 1 : this.index;
        <% onclick %>(this.count, this.index, '#<% id %>pageNum');
    }
    , fastRight : function() {
        this.index++;
        this.index = (this.index > 999) ? 1 : this.index;
        <% onclick %>(this.count, this.index, '#<% id %>pageNum');        
    }
    , leftLimit : function() {
        this.index = 1;
        <% onclick %>(this.count, this.index, '#<% id %>pageNum');        
    }
    , rightLimit : function() {
        this.index = 999;
        <% onclick %>(this.count, this.index, '#<% id %>pageNum');        
    }
});

