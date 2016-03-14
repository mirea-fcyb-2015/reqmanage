$(function () {
  
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
