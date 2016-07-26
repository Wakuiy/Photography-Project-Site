(function ($) {

/**
 * Soft scrolling on anchor
 */
$(function() {
  $('.scroll-anchor').on('click',function (e) {
      e.preventDefault();

      var target = this.hash;
      var $target = $(target);

      if(!$target.length) return;

      $('html, body').stop().animate({
          'scrollTop': $target.offset().top - 150
      }, 900, 'swing');
  });
});

})(jQuery);
