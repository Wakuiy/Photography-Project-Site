// function fixArea() {
//   var window_top = $(window).scrollTop();
//   var element_top = $('#block-homepagecategoryblocks').offset().top;
//   if (window_top > element_top) {
//     $('#block-homepagecategoryblocks .block-title').addClass('fixed');
//     $('#block-homepagecategoryblocks .block-title').height($('#block-homepagecategoryblocks').outerHeight());
//
//     console.log('should add .fixed class');
//
//   } else {
//     $('#block-homepagecategoryblocks .block-title').removeClass('fixed');
//     $('#block-homepagecategoryblocks .block-title').height(0);
//
//     console.log('should remove .fixed class');
//
//   }
// }
// (function() {
//
//   console.log('meh?');
//
//   $(window).scroll(fixArea);
//   fixArea();
// });

// ( function($) {
//
//   "use strict";
//
//   $(document).ready(function() {
//       var s = $("#block-homepagecategoryblocks .block-title");
//       var element_top = $("#block-homepagecategoryblocks").offset().top;
//       $(window).scroll(function() {
//           var windowpos = $(window).scrollTop();
//           if (windowpos >= element_top) {
//               s.addClass("fixed");
//           } else {
//               s.removeClass("fixed");
//           }
//       });
//     });
// })(jQuery);


( function($) {
  "use strict";

  var imageHover = function() {
    $('img').hover(function() {
      console.log('meh');
      $(this).addClass('img-hover');
    }, function() {
      $(this).removeClass('img-hover');
    });
  }

  $(document).ready(function() {
    imageHover();
  });



  // var toggleSticky = function() {
  //   var element_top = $("#block-homepagecategoryblocks").offset().top;
  //
  //   $(window).scroll(function() {
  //     var windowpos = $(window).scrollTop();
  //     $("#block-homepagecategoryblocks .views-element-container").toggleClass('fixed', windowpos >= element_top - 30);
  //     $(".footer").toggleClass('footer-fixed', windowpos >= element_top - 30);
  //   });
  // };
  //
  // $(document).ready(function() {
  //   // $('#block-homepagecategoryblocks .block-title').unwrap();
  //   // $('.block-title').wrap('<div></div>')
  //   toggleSticky();
  // });
  // $(window).resize(function() {
  //   toggleSticky();
  // });


  // $('img').click(function() {
  //   $('.region-content, .popup').animate({'opacity':'.50'}, 300, 'linear');
  //   $('.popup').animate({'opacity':'1.00'}, 300, 'linear');
  //   $('.region-content, .popup').css('display', 'block');
  // });
  //
  // function close_box() {
  //   $('.region-content, .popup').animate({'opacity':'0'}, 300, 'linear', function() {
  //     $('.popup').css('display', 'none');
  //   });
  //   $('.region-content').animate({'opacity':'1.00'}, 300, 'linear');
  //   console.log('meh');
  // };
  //
  // // $('.close').click(function() {
  // //   close_box();
  // // });
  //
  // $('body').click(function() {
  //   close_box();
  // });

})(jQuery);
