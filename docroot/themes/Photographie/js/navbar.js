(function ($) {
  var isNavBarShown = false;
  var scrolled = false;
    $(document).ready(function () {

        // hide .navbar first
        $("header").hide();

        // fade in .navbar
            $(window).scroll(function () {
                // set distance user needs to scroll before we fadeIn navbar
                if ($(this).scrollTop() > 100) {
                    $('header').fadeIn();
                    isNavBarShown = true;
                    scrolled = true;

                } else {
                $('header').fadeOut();
                isNavBarShown = false;
                scrolled = false;
            }
        });

        /* Show navbar */
        $('.hover-for-menu').hover(function () {
            if (isNavBarShown) {
              return;
            }
            $('header').fadeIn();
            isNavBarShown = true;
        });

        /* when navbar is hovered over it will override previous */
        $('.hover-for-menu').hover(function () {
            if (isNavBarShown) { return; }
            $('header').show();
        });

        $('.hover-for-menu').mouseleave(function () {
            if (isNavBarShown) {
              if (scrolled) {
                return;
              } else {
                $('header').fadeOut();
                isNavBarShown = false;
              }
            }
        });
    });
}(jQuery));
