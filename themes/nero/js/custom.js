
$ = jQuery.noConflict();




/* Support */

$("#slist a").click(function(e){
   e.preventDefault();
   $(this).next('p').toggle(200);
});

/* Drop down navigation */

ddlevelsmenu.setup("ddtopmenubar", "topbar");


/* Parallax Slider */
  $(function() {
    $('#da-slider').cslider({
      autoplay  : true,
      interval : 8000
    });
  });

/* Add class for galery image link */

$('.portfolio').find('a').addClass('prettyphoto');

/* Add class for galery image link */

$('div.gallery-image').find('a').addClass('prettyphoto');

/* prettyPhoto Gallery */

jQuery(".prettyphoto").prettyPhoto({
   overlay_gallery: false, social_tools: false
});

//Alter the class for last menu

$( ".nero-menu li:last-child .fa.fa-arrow-right.submenu-right-arrow" ).removeClass("fa fa-arrow-right submenu-right-arrow").addClass( "fa fa-arrow-left submenu-left-arrow" );

/* Isotype */

// cache container
var $container = $('#portfolio');
// initialize isotope
$container.isotope({
  // options...
});

// filter items when filter link is clicked
$('#filters a').click(function(){
  var selector = $(this).attr('data-filter');
  $container.isotope({ filter: selector });
  return false;
});

/* Carousel */

$('.carousel').carousel();


/* Navigation (Select box) */

// Create the dropdown base
$("<select />").appendTo(".navis");

// Create default option "Go to..."
$("<option />", {
   "selected": "selected",
   "value"   : "",
   "text"    : "Menu"
}).appendTo(".navis select");

// Populate dropdown with menu items
$(".navi a").each(function() {
 var el = $(this);
 $("<option />", {
     "value"   : el.attr("href"),
     "text"    : el.text()
 }).appendTo(".navis select");
});

$(".navis select").change(function() {
  window.location = $(this).find("option:selected").val();
});

/* *************************************** */
/* Scroll to Top */
/* *************************************** */

$(document).ready(function() {
	$(".totop").hide();

	$(window).scroll(function(){
		if ($(this).scrollTop() > 300) {
			$('.totop').fadeIn();
		} else {
			$('.totop').fadeOut();
		}
	});
	$(".totop a").click(function(e) {
		e.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});

});
/* *************************************** */
