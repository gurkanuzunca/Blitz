// Document ready
$(function() {

    $('#menu > ul > li > a').click(function(){
        var $this = $(this);

        if (! $this.next().is(':visible')){
            $('#menu li ul:visible').slideUp(200);
        }

        if ($this.next()){
            $this.next().slideDown(200);
        }
    });



});
