/**
 * Created by sriva on 20.02.2017.
 */
$(document).ready(function () {

        $(".close-popup").click(function () {
            $(".overlay-grey").fadeOut(200);
            $(".confirm").fadeOut(200);
        })

        $(".cancel").click(function () {
            $(".overlay-grey").fadeOut(200);
            $(".confirm").fadeOut(200);
        })


    $(".close-popup").click(function () {
        $(".overlay-grey").fadeOut(500);
        $(".disclaimer").fadeOut(100);
    })

});


$('.recommendator').click(function(e) {
    e.stopPropagation();
    var styles = {
        left : 800,
        height : "60%",
        paddingTop : "100px"
    }
    if(jQuery(window).width() < 500) {
        $(".overlay-grey").fadeIn(500);
        $('.disclaimer').show().addClass('disclaimer-mobile').css('top', 0);

    }
    else if (jQuery(window).width() > 500 && jQuery(window).width() < 1024) {

        $('.disclaimer').show().addClass('disclaimer-wideScreen').css(styles).css({'top': 380, 'left' : 520, 'padding-top' : '40px', 'height' : "70%"});
        $(".overlay-grey").fadeIn(100);
    }

    else if (jQuery(window).width() == 768 && jQuery(window).height() == 1024) {

        $('.disclaimer').show().addClass('disclaimer-wideScreen').css(styles).css({'top': 410, 'left' : 550, 'padding-top' : '65px', 'height' : '40%'});
        $(".overlay-grey").fadeIn(100);
    }

    else if (jQuery(window).width() == 1024 && jQuery(window).height() == 768) {

        $('.disclaimer').show().addClass('disclaimer-wideScreen').css(styles).css({'top': 410, 'left' : 550, 'padding-top' : '55px'});
        $(".overlay-grey").fadeIn(100);
    }

    else if (jQuery(window).width() == 1280) {

        $('.disclaimer').show().addClass('disclaimer-wideScreen').css(styles).css({'top': 420, 'left' : 650, 'padding-top' : '60px'});
        $(".overlay-grey").fadeIn(100);
    }

    else if (jQuery(window).width() == 1366 && jQuery(window).height() == 768) {

        $('.disclaimer')
        $(".overlay-grey").fadeIn(100);
    }

    else if (jQuery(window).height() > 900) {
        $('.disclaimer').removeClass('disclaimer-mobile').toggle();

        $('.disclaimer').show().addClass('disclaimer-desktop').css(styles).css('top', 380);
        $(".overlay-grey").fadeIn(100);
         }

    else {
        $('.disclaimer').removeClass('disclaimer-mobile').toggle();
        $(".overlay-grey").fadeIn(100);
    };
});

$('.delete-glyph').click(function(e) {
    e.stopPropagation();
    var confirmStyle = {
        width: "50%",
    height:"40%",
    left: "40%",
    top: "30%",
    marginLeft: "-250px"
    };
    if(jQuery(window).width() < 767) {
        $(".overlay-grey").fadeIn(500);
        $('.confirm').show().css('top', 0).css(confirmStyle).css({'align': 'center', 'left' : '80%'});

    } else if (jQuery(window).width() >= 767 && jQuery(window).width() <= 1400){
        $('.confirm').removeClass('disclaimer-mobile').css(confirmStyle).css({fontSize :"20px", width : "50%", height : "40%"}).toggle();
        $(".overlay-grey").fadeIn(100);
    }

    else {
        $('.confirm').removeClass('disclaimer-mobile').css(confirmStyle).css({fontSize :"24px", width : "40%", height : "30%"}).toggle();
        $('.closex').css({ "border-radius": "24px",
        "-moz-border-radius": "24px",
        "-webkit-border-radius": "24px", "width" : "48", "height" : "48", "fontSize": "18px"});
        $(".overlay-grey").fadeIn(100);
    }
});
// A pop-up for deleting the question
$('.ques-del').click(function(e) {
    e.stopPropagation();
    var confirmStyle = {
        width: "50%",
        height:"40%",
        left: "40%",
        top: "30%",
        marginLeft: "-250px"
    };
    if(jQuery(window).width() < 767) {
        $(".overlay-grey").fadeIn(500);
        $('.confirm').show().css('top', 0).css(confirmStyle).css({'align': 'center', 'left' : '80%'});

    } else if (jQuery(window).width() >= 767 && jQuery(window).width() <= 1400){
        $('.confirm').removeClass('disclaimer-mobile').css(confirmStyle).css({fontSize :"18px", width : "50%", height : "40%"}).toggle();
        $(".overlay-grey").fadeIn(100);
    }

    else {
        $('.confirm').removeClass('disclaimer-mobile').css(confirmStyle).css({fontSize :"28px", width : "40%", height : "30%"}).toggle();
        $('.confirm-dialogue').css('fontSize', '22px');
        $('.closex').css({ "border-radius": "12px",
            "-moz-border-radius": "12px",
            "-webkit-border-radius": "12px", "width" : "48", "height" : "48", "fontSize": "18px"});
        $(".overlay-grey").fadeIn(100);
    }
});

$('html').click(function() {
    $('.confirm').hide();
    $('.disclaimer').hide();
    $(".overlay-grey").fadeOut(500);
});

$(window).on('load', function () {
    $('li.gadget:first-child').addClass('active').css('backgroundColor', '#d0b5b4');
    $('div.gadgetInfo:first-child').fadeIn('fast');
});

var gad = $('li.gadget');
var gadInfo = $('div.gadgetInfo');
$.each(gad, function(i, item) {

    $(item).hover(function () {

        $(this).mouseout( function () {
            $(this).css('backgroundColor', 'transparent');
            if ($(this).hasClass('active')) {
                $(this).css('backgroundColor', '#d0b5b4');
            }
        });
        $(this).mouseover( function () {
            $(this).css('backgroundColor', '#d0b5b4');
        });

    });

    $(gad[i]).on('click', function () { // "or" item instead of gad[i]

        $(gad[i]).addClass('active').css('backgroundColor', '#d0b5b4');
        $(gad[i]).siblings().removeClass('active').css('backgroundColor', 'transparent');
        $(gadInfo[i]).fadeIn('fast');
       $(gadInfo[i]).siblings().animate({left:'-5%'}).hide();
    });
});

$(window).on('load', function () {
    $('#load').hide();
})

