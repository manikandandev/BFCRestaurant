function additionalCarousel(sliderId){
	/*======  curosol For Additional ==== */
	 var pxadditional = $(sliderId);
      pxadditional.owlCarousel({
     	 items : 4, //10 items above 1000px browser width
     	 itemsDesktop : [1199,3], 
     	 itemsDesktopSmall : [991,2], 
     	 itemsTablet: [480,2], 
     	 itemsMobile : [320,1] 
      });
      // Custom Navigation Events
      $(".additional_next").click(function(){
        pxadditional.trigger('owl.next');
      })
      $(".additional_prev").click(function(){
        pxadditional.trigger('owl.prev');
      });
}

$(document).ready(function(){
	
	bindGrid();
	additionalCarousel("#main #additional-carousel");
	
	var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
	if(!isMobile) {
		if($(".parallax").length){ $(".parallax").sitManParallex({  invert: false });};
	}else{
		$(".parallax").sitManParallex({  invert: true });
	}
	
	if(!isMobile) {
		if($(".parallax1").length){ $(".parallax1").sitManParallex({  invert: false });};
	}else{
		$(".parallax1").sitManParallex({  invert: true });
	}
	
	$('.cart_block .block_content').on('click', function (event) {
		event.stopPropagation();
	});
	
	
	// ---------------- home page blog slider setting ----------------------
	var psblog = $("#blog-carousel");
	psblog.owlCarousel({
	items : 3, //10 items above 1000px browser width
	itemsDesktop : [1199,2], 
	itemsDesktopSmall : [991,2], 
	itemsTablet: [479,1], 
	itemsMobile : [319,1] ,
	pagination:false
	});
	// Custom Navigation Events
	$(".blog_next").click(function(){
	psblog.trigger('owl.next');
	})
	$(".blog_prev").click(function(){
	psblog.trigger('owl.prev');
	});
	
	// ---------------- start more menu setting ----------------------
		var max_elem = 10;	
		var items = $('.menu ul#top-menu > li');	
		var surplus = items.slice(max_elem, items.length);
		
		surplus.wrapAll('<li class="category more_menu" id="more_menu"><div id="top_moremenu" class="popover sub-menu js-sub-menu collapse"><ul class="top-menu more_sub_menu">');
	
		$('.menu ul#top-menu .more_menu').prepend('<a href="#" class="dropdown-item" data-depth="0"><span class="pull-xs-right hidden-md-up"><span data-target="#top_moremenu" data-toggle="collapse" class="navbar-toggler collapse-icons"><i class="material-icons add">&#xE313;</i><i class="material-icons remove">&#xE316;</i></span></span></span>More</a>');
	
		$('.menu ul#top-menu .more_menu').mouseover(function(){
			$(this).children('div').css('display', 'block');
		})
		.mouseout(function(){
			$(this).children('div').css('display', 'none');
		});
	// ---------------- end more menu setting ----------------------

});

		if ($(document).width() <= 991){
			$('.news_button').click(function(event){
			$(this).toggleClass('active');
			event.stopPropagation();
			$(".newsblock").slideToggle("fast");
			
			});
		}
	
	
		if ($(document).width() <= 991){
		$('.about_button').click(function(event){
			$(this).toggleClass('active');
			event.stopPropagation();
			$("#aboutus").slideToggle("fast");
			
			});
		}
			

		if ($(document).width() > 767){

			$('.search_button').click(function(event){
			$(this).toggleClass('active');
			event.stopPropagation();
			$(".searchtoggle").slideToggle("fast");
			$( ".ui-autocomplete-input" ).focus();
			});
			$(".searchtoggle").on("click", function (event) {
			event.stopPropagation();
			});
		}

// Add/Remove acttive class on menu active in responsive  
	$('#menu-icon').on('click', function() {
		$(this).toggleClass('active');
	});

// Loading image before flex slider load
	$(window).load(function() { 
		$(".loadingdiv").removeClass("spinner"); 
	});

// Flex slider load
	$(window).load(function() {
		if($('.flexslider').length > 0){ 
			$('.flexslider').flexslider({		
				slideshowSpeed: $('.flexslider').data('interval'),
				pauseOnHover: $('.flexslider').data('pause'),
				animation: "fade"
			});
		}
	});		

// Scroll page bottom to top
	$(window).scroll(function() {
		if ($(this).scrollTop() > 500) {
			$('.top_button').fadeIn(500);
		} else {
			$('.top_button').fadeOut(500);
		}
	});							
	$('.top_button').click(function(event) {
		event.preventDefault();		
		$('html, body').animate({scrollTop: 0}, 800);
	});



/*======  Carousel Slider For Feature Product ==== */
	
	var pxfeature = $("#feature-carousel");
	pxfeature.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".feature_next").click(function(){
		pxfeature.trigger('owl.next');
	})
	$(".feature_prev").click(function(){
		pxfeature.trigger('owl.prev');
	});



/*======  Carousel Slider For New Product ==== */
	
	var pxnewproduct = $("#newproduct-carousel");
	pxnewproduct.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".newproduct_next").click(function(){
		pxnewproduct.trigger('owl.next');
	})
	$(".newproduct_prev").click(function(){
		pxnewproduct.trigger('owl.prev');
	});



/*======  Carousel Slider For Bestseller Product ==== */
	
	var pxbestseller = $("#bestseller-carousel");
	pxbestseller.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".bestseller_next").click(function(){
		pxbestseller.trigger('owl.next');
	})
	$(".bestseller_prev").click(function(){
		pxbestseller.trigger('owl.prev');
	});



/*======  Carousel Slider For Special Product ==== */
	var pxspecial = $("#special-carousel");
	pxspecial.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".special_next").click(function(){
		pxspecial.trigger('owl.next');
	})
	$(".special_prev").click(function(){
		pxspecial.trigger('owl.prev');
	});


/*======  Carousel Slider For Accessories Product ==== */

	var pxaccessories = $("#accessories-carousel");
	pxaccessories.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".accessories_next").click(function(){
		pxaccessories.trigger('owl.next');
	})
	$(".accessories_prev").click(function(){
		pxaccessories.trigger('owl.prev');
	});


/*======  Carousel Slider For Category Product ==== */

	var pxproductscategory = $("#productscategory-carousel");
	pxproductscategory.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".productscategory_next").click(function(){
		pxproductscategory.trigger('owl.next');
	})
	$(".productscategory_prev").click(function(){
		pxproductscategory.trigger('owl.prev');
	});


/*======  Carousel Slider For Viewed Product ==== */

	var pxviewed = $("#viewed-carousel");
	pxviewed.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".viewed_next").click(function(){
		pxviewed.trigger('owl.next');
	})
	$(".viewed_prev").click(function(){
		pxviewed.trigger('owl.prev');
	});

/*======  Carousel Slider For Crosssell Product ==== */

	var pxcrosssell = $("#crosssell-carousel");
	pxcrosssell.owlCarousel({
		items : 4, //10 items above 1000px browser width
		itemsDesktop : [1199,3], 
		itemsDesktopSmall : [991,2], 
		itemsTablet: [479,1], 
		itemsMobile : [319,1] 
	});
	// Custom Navigation Events
	$(".crosssell_next").click(function(){
		pxcrosssell.trigger('owl.next');
	})
	$(".crosssell_prev").click(function(){
		pxcrosssell.trigger('owl.prev');
	});

/*======  curosol For Manufacture ==== */
	 var pxbrand = $("#brand-carousel");
      pxbrand.owlCarousel({
     	 items : 4, //10 items above 1000px browser width
     	 itemsDesktop : [1199,4], 
     	 itemsDesktopSmall : [991,2],
     	 itemsTablet: [480,2], 
     	 itemsMobile : [320,1] 
      });
      // Custom Navigation Events
      $(".brand_next").click(function(){
        pxbrand.trigger('owl.next');
      })
      $(".brand_prev").click(function(){
        pxbrand.trigger('owl.prev');
      });
	  



/*======  Carousel Slider For For Tesimonial ==== */

	var pxtestimonial = $("#testimonial-carousel");
	pxtestimonial.owlCarousel({
		autoPlay: false,
		items : 1, //10 items above 1000px browser width
     	 itemsDesktop : [1199,1], 
     	 itemsDesktopSmall : [991,1],
     	 itemsTablet: [480,1], 
     	 itemsMobile : [320,1] 
	});
	
	// Custom Navigation Events
      $(".tmtestimonial_next").click(function(){
        pxtestimonial.trigger('owl.next');
      });

      $(".tmtestimonial_prev").click(function(){
        pxtestimonial.trigger('owl.prev');
      });
	  
	  //Userinfo toggle
	  
		$('.px_userinfotitle').click(function(event){	
		$(this).toggleClass('active');	
		event.stopPropagation();	
		$(".user-info").slideToggle("fast");	
		});	
		$(".user-info").on("click", function (event) {	
		event.stopPropagation();	
		});
	  



function bindGrid()
{
	var view = $.totalStorage("display");

	if (view && view != 'grid')
		display(view);
	else
		$('.display').find('li#grid').addClass('selected');

	$(document).on('click', '#grid', function(e){
		e.preventDefault();
		display('grid');
	});

	$(document).on('click', '#list', function(e){
		e.preventDefault();
		display('list');		
	});	
}

function display(view)
{
	if (view == 'list')
	{
		$('#products ul.product_list').removeClass('grid').addClass('list row');
		$('#products .product_list > li').removeClass('col-xs-12 col-sm-6 col-md-6 col-lg-4').addClass('col-xs-12');
		
		
		$('#products .product_list > li').each(function(index, element) {
			var html = '';
			html = '<div class="product-miniature js-product-miniature" data-id-product="'+ $(element).find('.product-miniature').data('id-product') +'" data-id-product-attribute="'+ $(element).find('.product-miniature').data('id-product-attribute') +'" itemscope itemtype="http://schema.org/Product"><div class="row">';
				html += '<div class="thumbnail-container col-xs-4 col-xs-5 col-md-4">' + $(element).find('.thumbnail-container').html() + '</div>';
				
				html += '<div class="product-description center-block col-xs-4 col-xs-7 col-md-8">';
					html += '<h3 class="h3 product-title" itemprop="name">'+ $(element).find('h3').html() + '</h3>';
					
					var price = $(element).find('.product-price-and-shipping').html();       // check : catalog mode is enabled
					if (price != null) {
						html += '<div class="product-price-and-shipping">'+ price + '</div>';
					}
					
					html += '<div class="product-detail">'+ $(element).find('.product-detail').html() + '</div>';
					
					var colorList = $(element).find('.highlighted-informations').html();
					if (colorList != null) {
						html += '<div class="highlighted-informations">'+ colorList +'</div>';
					}
					
					html += '<div class="product-actions">'+ $(element).find('.product-actions').html() +'</div>';
					
				html += '</div>';
			html += '</div></div>';
		$(element).html(html);
		});
		$('.display').find('li#list').addClass('selected');
		$('.display').find('li#grid').removeAttr('class');
		$.totalStorage('display', 'list');
	}
	else
	{
		$('#products ul.product_list').removeClass('list').addClass('grid row');
		$('#products .product_list > li').removeClass('col-xs-12').addClass('col-xs-12 col-sm-6 col-md-6 col-lg-4');
		$('#products .product_list > li').each(function(index, element) {
		var html = '';
		html += '<div class="product-miniature js-product-miniature" data-id-product="'+ $(element).find('.product-miniature').data('id-product') +'" data-id-product-attribute="'+ $(element).find('.product-miniature').data('id-product-attribute') +'" itemscope itemtype="http://schema.org/Product">';
			html += '<div class="thumbnail-container">' + $(element).find('.thumbnail-container').html() +'</div>';
			
			html += '<div class="product-description">';
				html += '<h3 class="h3 product-title" itemprop="name">'+ $(element).find('h3').html() +'</h3>';
			
				var price = $(element).find('.product-price-and-shipping').html();       // check : catalog mode is enabled
				if (price != null) {
					html += '<div class="product-price-and-shipping">'+ price + '</div>';
				}
				
				html += '<div class="product-detail">'+ $(element).find('.product-detail').html() + '</div>';
				
				html += '<div class="product-actions">'+ $(element).find('.product-actions').html() +'</div>';
				
				var colorList = $(element).find('.highlighted-informations').html();
				if (colorList != null) {
					html += '<div class="highlighted-informations">'+ colorList +'</div>';
				}
				
			html += '</div>';
		html += '</div>';
		$(element).html(html);
		});
		$('.display').find('li#grid').addClass('selected');
		$('.display').find('li#list').removeAttr('class');
		$.totalStorage('display', 'grid');
	}
}


function responsivecolumn(){
	
	if ($(document).width() <= 767){
				
		// ---------------- Fixed header responsive ----------------------
		$(window).bind('scroll', function () {
			if ($(window).scrollTop() > 0) {
				$('.header-nav').addClass('fixed');
			} else {
				$('.header-nav').removeClass('fixed');
			}
		});
	}
	
	
	if ($(document).width() <= 991)
	{
		$('.container #columns_inner #left-column').appendTo('.container #columns_inner');
		
	}
	else if($(document).width() >= 992)
	{
		$('.container #columns_inner #left-column').prependTo('.container #columns_inner');
		
	}
}
$(document).ready(function(){responsivecolumn();});
$(window).resize(function(){responsivecolumn();});