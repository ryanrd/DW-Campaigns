;(function() {
    dw_campaigns_derby = {
        
        /**
         * init cancer
         */
        init: function() {
            
                $('body:not(.donation-page, .dw-user-register, .dw-user-profile) select').parent('*').jqTransform(); 
        	/**
        	 * Error div
        	 */
        	$('#console .limiter div:first').hide()
        						  .fadeIn()
        						  .center()
        					      .append('<a href="#" class="close">X</a>')
        					      .click(function () {
        					      		$(this).fadeOut();
        					      });
        }
    };  
})();

$(document).ready(function(e) {
    dw_campaigns_derby.init();
});


jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
    this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
    return this;
}
