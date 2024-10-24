// open Weading Categories
// $(function() {
//   "use strict";

//Loader	
      $(function preloaderLoad() {
      if($('.preloader').length){
          $('.preloader').delay(300).fadeOut(300);
      }
      $(".preloader_disabler").on('click', function() {
          $("#preloader").hide();
      });
  });


// stickey nav
$(window).on('scroll', function () {
  let scrollTop = $(this).scrollTop();

  if (scrollTop >= 50) {
    $('#navbar').addClass('sticky');
  } else {
    $('#navbar').removeClass('sticky');
  }
});
 
// +====================================

$(document).ready(function(){
  $(".dropdown").hover(function(){
      var dropdownMenu = $(this).children(".dropdown-menu");
      if(dropdownMenu.is(":visible")){
          dropdownMenu.parent().toggleClass("open");
      }
  });
});   
 

$('.sliders').slick({
  dots: false,
  infinite: true,
  speed: 3000, 
  slidesToShow: 6,
  slidesToScroll: 3,
  autoplay: true,
  arrows: true,
  responsive: [{
    breakpoint: 1000,
    settings: {
      slidesToShow: 3,
      slidesToScroll: 3,
      infinite: true,
      dots: false,
      arrows: false
    }
  },
  {
    breakpoint: 600,
    settings: {
      slidesToShow: 2,
      slidesToScroll: 2,
      dots: false,
      arrows: false
    }
  },
  {
    breakpoint: 480,
    settings: {
      slidesToShow: 2,
      slidesToScroll: 1,
      dots: false,
      arrows: false
    }
  }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
// *****************************************************
$('.ourstar').slick({
  dots: false,
  infinite: true,
  speed: 3000, 
  slidesToShow: 4,
  slidesToScroll: 3,
  autoplay: true,
  arrows: true,
  responsive: [{
    breakpoint: 1000,
    settings: {
      slidesToShow: 3,
      slidesToScroll: 3,
      infinite: true,
      dots: false,
      arrows: false 
    }
  },
  {
    breakpoint: 600,
    settings: {
      slidesToShow: 2,
      slidesToScroll: 2,
      dots: false,
      arrows: false
    }
  },
  {
    breakpoint: 480,
    settings: {
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false

    }
  }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

 // *****************************************************
$('.quiz').slick({
  dots: false,
  infinite: true,
  speed: 3000, 
  slidesToShow: 2,
  slidesToScroll: 3,
  autoplay: true,
  arrows: true,
  responsive: [{
    breakpoint: 1000,
    settings: {
      slidesToShow: 3,
      slidesToScroll: 3,
      infinite: true,
      dots: false,
      arrows: false 
    }
  },
  {
    breakpoint: 600,
    settings: {
      slidesToShow: 1,
      slidesToScroll: 2,
      dots: false,
      arrows: false
    }
  },
  {
    breakpoint: 480,
    settings: {
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false

    }
  }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

// / *****************************************************
$('.featuredCourses').slick({
  dots: false,
  infinite: true,
  speed: 3000, 
  slidesToShow: 3,
  slidesToScroll: 3,
  autoplay: true,
  arrows: true,
  responsive: [{
    breakpoint: 1000,
    settings: {
      slidesToShow: 2,
      slidesToScroll: 3,
      infinite: true,
      dots: false,
      arrows: false 
    }
  },
  {
    breakpoint: 600,
    settings: {
      slidesToShow: 1,
      slidesToScroll: 2,
      dots: false,
      arrows: false
    }
  },
  {
    breakpoint: 480,
    settings: {
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false

    }
  }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});

 
//  counter js ********************
$(document).ready(function() {

  var counters = $(".count");
  var countersQuantity = counters.length;
  var counter = []; 
  
  for (i = 0; i < countersQuantity; i++) {
    counter[i] = parseInt(counters[i].innerHTML);
  }

  var count = function(start, value, id) {
    var localStart = start;
    setInterval(function() {
      if (localStart < value) {
        localStart++;
        counters[id].innerHTML = localStart;
      }
    }, 40);
  }

  for (j = 0; j < countersQuantity; j++) {
    count(0, counter[j], j);
  }
});
 