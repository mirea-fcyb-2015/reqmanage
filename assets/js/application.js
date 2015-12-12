$(function () {
    

    $('body').on('click', 'ul.acc-menu a', function() {
        var LIs = $(this).closest('ul.acc-menu').children('li');
        $(this).closest('li').addClass('clicked');
        $.each( LIs, function(i) {
            if( $(LIs[i]).hasClass('clicked') ) {
                $(LIs[i]).removeClass('clicked');
                return true;
            }
            if($.cookie('admin_leftbar_collapse') !== 'collapse-leftbar' || $(this).parents('.acc-menu').length > 1) $(LIs[i]).find('ul.acc-menu:visible').slideToggle();
            $(LIs[i]).removeClass('open');
        });
        if($(this).siblings('ul.acc-menu:visible').length>0)
            $(this).closest('li').removeClass('open');
        else
            $(this).closest('li').addClass('open');
            if($.cookie('admin_leftbar_collapse') !== 'collapse-leftbar' || $(this).parents('.acc-menu').length > 1) $(this).siblings('ul.acc-menu').slideToggle({
                duration: 200,
                progress: function(){
                    checkpageheight();
                    if ($(this).closest('li').is(":last-child")) { //only scroll down if last-child
                        $("#sidebar").animate({ scrollTop: $("#sidebar").height()},0);
                    }

                },
                complete: function(){
                    $("#sidebar").getNiceScroll().resize();
                }
            });
    });

    var targetAnchor;
    $.each ($('ul.acc-menu a'), function() {
       //console.log(this.href);
       
        if( this.href == window.location ) {
            targetAnchor = this;
            return false;
        }
    });

    var parent = $(targetAnchor).closest('li');
    while(true) {
        parent.addClass('active');
        parent.closest('ul.acc-menu').show().closest('li').addClass('open');
        parent = $(parent).parents('li').eq(0);
        if( $(parent).parents('ul.acc-menu').length <= 0 ) break;
    }

    var liHasUlChild = $('li').filter(function(){
        return $(this).find('ul.acc-menu').length;
    });
    $(liHasUlChild).addClass('hasChild');

    if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar') {
        $('ul.acc-menu:first ul.acc-menu').css('visibility', 'hidden');
    }
    $('ul.acc-menu:first > li').hover(function() {
        if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar')
            $(this).find('ul.acc-menu').css('visibility', '');
    }, function() {
        if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar')
            $(this).find('ul.acc-menu').css('visibility', 'hidden');
    });

    // Reads Cookie for Collapsible Leftbar 
    // if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar')
    //     $("body").addClass("collapse-leftbar");

    //Make only visible area scrollable
    $("#widgetarea").css({"max-height":$("body").height()});
    //Bind widgetarea to nicescroll
    $("#widgetarea").niceScroll({horizrailenabled:false});


    //Will open menu if it has link
    //$('.hasChild.active ul.acc-menu').slideToggle({duration: 200});

    // Toggle Buttons
    // ------------------------------

    //On click of left menu
    $("a#leftmenu-trigger").click(function () {
        if ((window.innerWidth)<768) {
            $("body").toggleClass("show-leftbar");
        } else {
            $("body").toggleClass("collapse-leftbar");

            //Sets Cookie for Toggle
            if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar') {
                $.cookie('admin_leftbar_collapse', '');
                $('ul.acc-menu').css('visibility', '');

            } else {
                $.each($('.acc-menu'), function() {
                    if($(this).css('display') == 'none')
                        $(this).css('display', '');
                });
                
                $('ul.acc-menu:first ul.acc-menu').css('visibility', 'hidden');
                $.cookie('admin_leftbar_collapse', 'collapse-leftbar');
            }
        }
        checkpageheight();
        leftbarScrollShow();
    });

    // On click of right menu
    $("a#rightmenu-trigger").click(function () {
        $("body").toggleClass("show-rightbar");
        widgetheight();
        
        if($.cookie('admin_rightbar_show') === 'show-rightbar')
                $.cookie('admin_rightbar_show', '');
            else
                $.cookie('admin_rightbar_show', 'show-rightbar');
    });

    //set minimum height of page
    dh=($(document).height()-40);
    $("#page-content").css("min-height",dh+"px");
    //checkpageheight();

});

// Recalculate widget area on a widget being shown
$(".widget-body").on('shown.bs.collapse', function () {
    widgetheight();
});

// -------------------------------
// Sidebars Positionings
// -------------------------------

$(window).scroll(function(){
    $("#widgetarea").getNiceScroll().resize();
    $(".chathistory").getNiceScroll().resize();
    rightbarTopPos();
    leftbarTopPos();
});

$(window).resize(function(){
    widgetheight();

    rightbarRightPos();
    $("#sidebar").getNiceScroll().resize();
});
rightbarRightPos();


// -------------------------------
// Mobile Only - set sidebar as fixed position, slide
// -------------------------------

enquire.register("screen and (max-width: 767px)", {
    match : function() {
        // For less than 768px
        $(function() {

            //Bind sidebar to nicescroll
            $("#sidebar").niceScroll({horizrailenabled:false});
            leftbarScrollShow();

            //Click on body and hide leftbar
            $("#wrap").click(function(){
                if ($("body").hasClass("show-leftbar")) {
                    $("body").removeClass("show-leftbar");
                    leftbarScrollShow();
                }
            });

            //Fix a bug
            $('#sidebar ul.acc-menu').css('visibility', '');

            //open up leftbar
            $("body").removeClass("show-leftbar");
            $.removeCookie("admin_leftbar_collapse");

            $("body").removeClass("collapse-leftbar");

        });

        console.log("match");
    },
    unmatch : function() {

        //Remove nicescroll to clear up some memory
            $("#sidebar").niceScroll().remove();
            $("#sidebar").css("overflow","visible");

        console.log("unmatch");

        //hide leftbar
        $("body").removeClass("show-leftbar");

    }
});

//Helper functions
//---------------

//Fixing the show of scroll rails even when sidebar is hidden
function leftbarScrollShow () {
    if ($("body").hasClass("show-leftbar")) {
        $("#sidebar").getNiceScroll().show();
    } else {
        $("#sidebar").getNiceScroll().hide();
    }
    $("#sidebar").getNiceScroll().resize();
}

//set Top positions for changing between static and fixed header
function leftbarTopPos() {
    var scr=$('body.static-header').scrollTop();
    if (scr<41) {$('ul#sidebar').css('top',40-scr + 'px');} else {$('ul#sidebar').css('top',0);}
}

function rightbarTopPos() {
    var scr=$('body.static-header').scrollTop();
    if (scr<41) {$('#page-rightbar').css('top',40-scr + 'px');} else {$('#page-rightbar').css('top',0);}
}

//Set Right position for fixed layouts
function rightbarRightPos () {
    if ($('body').hasClass('fixed-layout')) {
        var $pc = $('#page-content');
        var ending_right = ($(window).width() - ($pc.offset().left + $pc.outerWidth()));
        if (ending_right<0) ending_right=0;
        $('#page-rightbar').css('right',ending_right);
    }
}

// Match page height with Sidebar Height
function checkpageheight() {
    sh=$("#page-leftbar").height();
    ch=$("#page-content").height();

    if (sh>ch) $("#page-content").css("min-height",sh+"px");
}

// Recalculate widget area to area visible
function widgetheight() {
    $("#widgetarea").css({"max-height":$("body").height()});
    $("#widgetarea").getNiceScroll().resize();
}

// -------------------------------
// Back to Top button
// -------------------------------

$('#back-to-top').click(function () {
    $('body,html').animate({
        scrollTop: 0
    }, 500);
    return false;
});

// -------------------------------
// Panel Collapses
// -------------------------------
$('a.panel-collapse').click(function() {
    $(this).children().toggleClass("fa-chevron-down fa-chevron-up");
    $(this).closest(".panel-heading").next().slideToggle({duration: 200});
    $(this).closest(".panel-heading").toggleClass('rounded-bottom');
    return false;
});

// -------------------------------
// Quick Start
// -------------------------------
$('#headerbardropdown').click(function() {
    $('#headerbar').css('top',0);
    return false;
});

$('#headerbardropdown').click(function(event) {
  $('html').one('click',function() {
    $('#headerbar').css('top','-1000px');
  });

  event.stopPropagation();
});


// -------------------------------
// Keep search open on click
// -------------------------------
$('#search>a').click(function () {
    $('#search').toggleClass('keep-open');
    $('#search>a i').toggleClass("opacity-control");
});

$('#search').click(function(event) {
  $('html').one('click',function() {
    $('#search').removeClass('keep-open');
    $('#search>a i').addClass("opacity-control");
  });

  event.stopPropagation();
});

//Presentational: set all panel-body with br0 if it has panel-footer
$(".panel-footer").prev().css("border-radius","0");




/// DEMO ======================================

// -------------------------------
// Demos
// -------------------------------
$(document).ready(
  function() {
    $('.popovers').popover({container: 'body', trigger: 'hover', placement: 'top'}); //bootstrap's popover
    $('.tooltips').tooltip(); //bootstrap's tooltip

    $(".chathistory").niceScroll({horizrailenabled:false});  //chathistory scroll

    try {
        //Set nicescroll on notifications
        $(".scrollthis").niceScroll({horizrailenabled:false});
        $('.dropdown').on('shown.bs.dropdown', function () {
            $(".scrollthis").getNiceScroll().resize();
            $(".scrollthis").getNiceScroll().show();
        });
        $('.dropdown').on('hide.bs.dropdown', function ()  {
            $(".scrollthis").getNiceScroll().hide();
        });

        $(window).scroll(function(){
            $(".scrollthis").getNiceScroll().resize();
        });
    } catch(e) {}

    prettyPrint(); //Apply Code Prettifier

    $('.toggle').toggles({on:true});

    //EasyPieChart in rightbar

    try {
    $('.easypiechart#serverload').easyPieChart({
        barColor: "#e73c3c",
        trackColor: '#edeef0',
        scaleColor: '#d2d3d6',
        scaleLength: 5,
        lineCap: 'round',
        lineWidth: 2,
        size: 90,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });

    $('.easypiechart#ramusage').easyPieChart({
        barColor: "#f39c12",
        trackColor: '#edeef0',
        scaleColor: '#d2d3d6',
        scaleLength: 5,
        lineCap: 'round',
        lineWidth: 2,
        size: 90,
        onStep: function(from, to, percent) {
            $(this.el).find('.percent').text(Math.round(percent));
        }
    });
    }
    catch(error) {}



    $("#currentbalance").sparkline([12700,8573,10145,21077,15380,14399,19158,23911,15401,16793,13115,23315], {
    type: 'bar',
    barColor: '#62bc1f',
    height: '45',
    barWidth: 7});

});


// -------------------------------
// Demo: Chatbar.
// -------------------------------

$('.chatinput textarea').keypress(function (e) {
  if (e.which == 13) {

    var chatmsg = $(".chatinput textarea").val();
    var oo=$(".chathistory").html();

    var d=new Date();
    var n=d.toLocaleTimeString();


    if (!!$(".chatinput textarea").val())
        $(".chathistory").html(oo+ "<div class='chatmsg'><p>"+chatmsg+"</p><span class='timestamp'>"+n+"</span></div>");


    $(".chathistory").getNiceScroll().resize();
    $(".chathistory").animate({ scrollTop: $(document).height() }, 0);

    $(this).val(''); // empty textarea

    return false;
  }
});


// Toggle buttons

$("a#hidechatbtn").click(function () {
    $("#widgetarea").toggle();
    $("#chatarea").toggle();
});

$("#chatbar li a").click(function () {
    $("#widgetarea").toggle();
    $("#chatarea").toggle();
});




// -------------------------------
// Show Theme Settings
// -------------------------------
$('#slideitout').click(function() {
    $('#demo-theme-settings').toggleClass('shown');
    return false;
});


// -------------------------------
// Demo: Theme Settings
// -------------------------------

// Demo Fixed Header

if($.cookie('fixed-header') === 'navbar-static-top') {
    $('#fixedheader').toggles();
} else {
    $('#fixedheader').toggles({on:true});
}

$('.dropdown-menu').on('click', function(e){
    if($(this).hasClass('dropdown-menu-form')){
        e.stopPropagation();
    }
});


$('#fixedheader').on('toggle', function (e, active) {
    $('header').toggleClass('navbar-fixed-top navbar-static-top');
    $('body').toggleClass('static-header');
    rightbarTopPos();
    if (active) {
        $.removeCookie('fixed-header');
    } else {
        $.cookie('fixed-header', 'navbar-static-top');
    }
});

// Demo Color Variation
// Read the CSS files from data attributes

$("#demo-color-variations a").click(function(){
    $("head link#styleswitcher").attr("href", 'assets/demo/variations/' + $(this).data("theme"));
    $.cookie('theme',$(this).data("theme"));
    return false;
});

$("#demo-header-variations a").click(function(){
    $("head link#headerswitcher").attr("href", 'assets/demo/variations/' + $(this).data("headertheme"));
    $.cookie('headertheme',$(this).data("headertheme"));
    return false;
});

//Demo Background Pattern

$(".demo-blocks").click(function(){
    $('.fixed-layout').css('background',$(this).css('background'));
    return false;
});