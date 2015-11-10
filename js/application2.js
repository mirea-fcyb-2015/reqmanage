// ------------------------------
// Sidebar Accordion Menu
// ------------------------------

$(function () {
    $('body').on('click', 'ul.acc-menu a', function() {
        var LIs = $(this).closest('ul.acc-menu').children('li');
        $(this).closest('li').addClass('clicked');
        $.each( LIs, function(i) {
            if( $(LIs[i]).hasClass('clicked') ) {
                $(LIs[i]).removeClass('clicked');
                return true;
            }
            $(LIs[i]).find('ul.acc-menu:visible').slideToggle();
            $(LIs[i]).removeClass('open');
        });
        if($(this).siblings('ul.acc-menu:visible').length>0)
            $(this).closest('li').removeClass('open');
        else
            $(this).closest('li').addClass('open');
            $(this).siblings('ul.acc-menu').slideToggle({
                duration: 200,
                progress: function(){
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

    // Reads Cookie for Collapsible Leftbar 
    // if($.cookie('admin_leftbar_collapse') === 'collapse-leftbar')
    //     $("body").addClass("collapse-leftbar");

    //Make only visible area scrollable
    $("#widgetarea").css({"max-height":$("body").height()});
    //Bind widgetarea to nicescroll
    // $("#widgetarea").niceScroll({horizrailenabled:false});


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
