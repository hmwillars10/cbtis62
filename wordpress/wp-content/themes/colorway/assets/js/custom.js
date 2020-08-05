
jQuery(function() {
    jQuery('ul.sm.sm-mint').smartmenus();
});
jQuery(function(){
    jQuery( "sm" ).append( "<li></li>" );
});
jQuery(document).ready(function(){
    jQuery(".columns img, .post .postimg").hover(function() {
        jQuery(this).stop().animate({
            opacity: "0.8"
        }, '300');
    },
    function() {
        jQuery(this).stop().animate({
            opacity: "1"
        }, '300');
    });
    jQuery('#menu li.current_page_item a').click(function(){
       jQuery(this).css('border-radius','5px'); 
    });
});
jQuery(document).ready(function () {
   jQuery('.cw-content').removeClass('container-head');
});
jQuery(document).ready(function (jQuery) {
    var animated_element = jQuery('.animated');
    function image_animation() {
        animated_element.each(function () {
            var get_offset = jQuery(this).offset();
            if (jQuery(window).scrollTop() + jQuery(window).height() > get_offset.top + jQuery(this).height() / 2) {
                jQuery(this).addClass('animation_started');
                // var el = $(this).find('.animated');
                setTimeout(function () {
                    jQuery(this).removeClass('animated').removeAttr('style');
                }, 300);
            }
        });
    }
    jQuery(window).scroll(function () {
        image_animation();
    });
    jQuery(window).load(function () {
        setInterval(image_animation, 300);
    });
});