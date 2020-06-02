/*

	Supersized - Fullscreen Slideshow jQuery Plugin
	Version : 3.2.1
	Site	: www.buildinternet.com/project/supersized
	
	Author	: Sam Dunn
	Company : One Mighty Roar (www.onemightyroar.com)
	License : MIT License / GPL License
	
*/

jQuery.noConflict();

(function(jQuery){

	/* Place Supersized Elements
	----------------------------*/
	jQuery(document).ready(function() {
		jQuery('body').append('<div id="supersized-loader"></div><div id="supersized"></div>');
	});
    
    
    jQuery.supersized = function(options){
    	
    	/* Variables
		----------------------------*/
    	var el = '#supersized',
        	base = this;
        // Access to jQuery and DOM versions of element
        base.jQueryel = jQuery(el);
        base.el = el;
        vars = jQuery.supersized.vars;
        // Add a reverse reference to the DOM object
        base.jQueryel.data("supersized", base);
        api = base.jQueryel.data('supersized');
		
		base.init = function(){
        	// Combine options and vars
        	jQuery.supersized.vars = jQuery.extend(jQuery.supersized.vars, jQuery.supersized.themeVars);
        	jQuery.supersized.vars.options = jQuery.extend({},jQuery.supersized.defaultOptions, jQuery.supersized.themeOptions, options);
            base.options = jQuery.supersized.vars.options;
            
            base._build();
            base.joomspirit();
        };
        
        
        /* Build Elements
		----------------------------*/
        base._build = function(){
        	// Add in slide markers
        	var thisSlide = 0,
				markers = '',
				markerContent,
				thumbMarkers = '',
				thumbImage;
				
			while(thisSlide <= base.options.slides.length-1){
				//Determine slide link content
				switch(base.options.slide_links){
					case 'num':
						markerContent = thisSlide;
						break;
					case 'name':
						markerContent = base.options.slides[thisSlide].title;
						break;
					case 'blank':
						markerContent = '';
						break;
				}
				
				if(thisSlide == base.options.start_slide-1){
					// Slide links
					if (base.options.slide_links)markers = markers+'<li class="slide'+thisSlide+' current-slide"><a>'+markerContent+'</a></li>';
					// Slide Thumbnail Links
					if (base.options.thumb_links){
						base.options.slides[thisSlide].thumb ? thumbImage = base.options.slides[thisSlide].thumb : thumbImage = base.options.slides[thisSlide].image;
						thumbMarkers = thumbMarkers+'<li class="thumb'+thisSlide+' current-thumb"><img src="'+thumbImage+'"/></li>';
					};
				}else{
					// Slide links
					if (base.options.slide_links) markers = markers+'<li class="slide'+thisSlide+'"><a>'+markerContent+'</a></li>';
					// Slide Thumbnail Links
					if (base.options.thumb_links){
						base.options.slides[thisSlide].thumb ? thumbImage = base.options.slides[thisSlide].thumb : thumbImage = base.options.slides[thisSlide].image;
						thumbMarkers = thumbMarkers+'<li class="thumb'+thisSlide+'"><img src="'+thumbImage+'"/></li>';
					};
				}
				thisSlide++;
			}
			if (base.options.slide_links) jQuery(vars.slide_list).html(markers);
			if (base.options.thumb_links && vars.thumb_tray.length){
				jQuery(vars.thumb_tray).append('<ul id="'+vars.thumb_list.replace('#','')+'"></ul>');
					jQuery(vars.thumb_list).html(thumbMarkers);
			}
			
			// Add in thumbnails
			if (base.options.thumbnail_navigation){
				// Load previous thumbnail
				vars.current_slide - 1 < 0  ? prevThumb = base.options.slides.length - 1 : prevThumb = vars.current_slide - 1;
				jQuery(vars.prev_thumb).show().html(jQuery("<img/>").attr("src", base.options.slides[prevThumb].image));
				
				// Load next thumbnail
				vars.current_slide == base.options.slides.length - 1 ? nextThumb = 0 : nextThumb = vars.current_slide + 1;
				jQuery(vars.next_thumb).show().html(jQuery("<img/>").attr("src", base.options.slides[nextThumb].image));
			}
			
            base._start(); // Get things started
        };
        
        
		//
		// 	JoomSpirit code
		//
		
		/* JoomSpirit code
		----------------------------*/
		base.joomspirit = function(){
					
			if (base.options.show_thumb == 0){
		    	jQuery('#thumb-tray').addClass('hide_thumbnails');		//Hide thumbnails
			}			
								
		};
					
		

					
		// 	end of JoomSpirit code        
        
        
        /* Initialize
		----------------------------*/
    	base._start = function(){
			
			// Determine if starting slide random
			if (base.options.start_slide){
				vars.current_slide = base.options.start_slide - 1;
			}else{
				vars.current_slide = Math.floor(Math.random()*base.options.slides.length);	// Generate random slide number
			}
			
			// If links should open in new window
			var linkTarget = base.options.new_window ? ' target="_blank"' : '';
			
			// Set slideshow quality (Supported only in FF and IE, no Webkit)
			if (base.options.performance == 3){
				base.jQueryel.addClass('speed'); 		// Faster transitions
			} else if ((base.options.performance == 1) || (base.options.performance == 2)){
				base.jQueryel.addClass('quality');	// Higher image quality
			}
						
			// Shuffle slide order if needed		
			if (base.options.random){
				arr = base.options.slides;
				for(var j, x, i = arr.length; i; j = parseInt(Math.random() * i), x = arr[--i], arr[i] = arr[j], arr[j] = x);	// Fisher-Yates shuffle algorithm (jsfromhell.com/array/shuffle)
			    base.options.slides = arr;
			}
			
			/*-----Load initial set of images-----*/
	
			if (base.options.slides.length > 1){
				// Set previous image
				vars.current_slide - 1 < 0  ? loadPrev = base.options.slides.length - 1 : loadPrev = vars.current_slide - 1;	// If slide is 1, load last slide as previous
				var imageLink = (base.options.slides[loadPrev].url) ? "href='" + base.options.slides[loadPrev].url + "'" : "";
				
				var imgPrev = jQuery('<img src="'+base.options.slides[loadPrev].image+'"/>');
				imgPrev.appendTo(base.el).wrap('<a class="image-loading" ' + imageLink + linkTarget + '></a>');
			
				imgPrev.load(function(){
					jQuery(this).data('origWidth', jQuery(this).width()).data('origHeight', jQuery(this).height());
					base.resizeNow();	// Resize background image
				});	// End Load
			} else {
				// Slideshow turned off if there is only one slide
				base.options.slideshow = 0;
			}
			
			// Set current image
			imageLink = (api.getField('url')) ? "href='" + api.getField('url') + "'" : "";
			var img = jQuery('<img src="'+api.getField('image')+'"/>');
			img.appendTo(base.el).wrap('<a class="image-loading activeslide" ' + imageLink + linkTarget + '></a>').css('visibility','hidden');
			
			img.load(function(){
				base._origDim(jQuery(this));
				base.resizeNow();	// Resize background image
				base.launch();
				if( typeof theme != 'undefined' && typeof theme._init == "function" ) theme._init();	// Load Theme
			});
			
			if (base.options.slides.length > 1){
				// Set next image
				vars.current_slide == base.options.slides.length - 1 ? loadNext = 0 : loadNext = vars.current_slide + 1;	// If slide is last, load first slide as next
				imageLink = (base.options.slides[loadNext].url) ? "href='" + base.options.slides[loadNext].url + "'" : "";
				
				var imgNext = jQuery('<img src="'+base.options.slides[loadNext].image+'"/>');
				imgNext.appendTo(base.el).wrap('<a class="image-loading" ' + imageLink + linkTarget + '></a>');
			
				imgNext.load(function(){
					jQuery(this).data('origWidth', jQuery(this).width()).data('origHeight', jQuery(this).height());
					base.resizeNow();	// Resize background image
				});	// End Load
			}
			/*-----End load initial images-----*/
			
			//  Hide elements to be faded in
			base.jQueryel.css('visibility','hidden');
			jQuery('.load-item').hide();
			
    	};
		
		
		/* Launch Supersized
		----------------------------*/
		base.launch = function(){
		
			base.jQueryel.css('visibility','visible');
			jQuery('#supersized-loader').hide();		//Hide loading animation
			
			// Call theme function for before slide transition
			if( typeof theme != 'undefined' && typeof theme.beforeAnimation == "function" ) theme.beforeAnimation('next');
			jQuery('.load-item').show();
			
			// Keyboard Navigation
			if (base.options.keyboard_nav){
				jQuery(document.documentElement).keyup(function (event) {
				
					if(vars.in_animation) return false;		// Abort if currently animating
					
					// Left Arrow or Down Arrow
					if ((event.keyCode == 37) || (event.keyCode == 40)) {
						clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup
						base.prevSlide();
					
					// Right Arrow or Up Arrow
					} else if ((event.keyCode == 39) || (event.keyCode == 38)) {
						clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup
						base.nextSlide();
					
					// Spacebar	
					} else if (event.keyCode == 32 && !vars.hover_pause) {
						clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup
						base.playToggle();
					}
				
				});
			}
			
			// Pause when hover on image
			if (base.options.slideshow && base.options.pause_hover){
				jQuery(base.el).hover(function() {
					if(vars.in_animation) return false;		// Abort if currently animating
			   			vars.hover_pause = true;	// Mark slideshow paused from hover
			   			if(!vars.is_paused){
			   				vars.hover_pause = 'resume';	// It needs to resume afterwards
			   				base.playToggle();
			   			}
			   	}, function() {
					if(vars.hover_pause == 'resume'){
						base.playToggle();
						vars.hover_pause = false;
					}
			   	});
			}
			
			if (base.options.slide_links){
				// Slide marker clicked
				jQuery('li', vars.slide_list).click(function(){
				
					index = jQuery('li', vars.slide_list).index(this);
					targetSlide = index + 1;
					
					base.goTo(targetSlide);
					return false;
					
				});
			}
			
			// Thumb marker clicked
			if (base.options.thumb_links){
				jQuery('li', vars.thumb_list).click(function(){
				
					index = jQuery('li', vars.thumb_list).index(this);
					targetSlide = index + 1;
					
					api.goTo(targetSlide);
					return false;
					
				});
			}
			
			// Start slideshow if enabled
			if (base.options.slideshow && base.options.slides.length > 1){
	    		
	    		// Start slideshow if autoplay enabled
	    		if (base.options.autoplay && base.options.slides.length > 1){
	    			vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);	// Initiate slide interval
				}else{
					vars.is_paused = true;	// Mark as paused
				}
				
				//Prevent navigation items from being dragged					
				jQuery('.load-item img').bind("contextmenu mousedown",function(){
					return false;
				});
								
			}
			
			// Adjust image when browser is resized
			jQuery(window).resize(function(){
	    		base.resizeNow();
			});
    		
    	};
        
        
        /* Resize Images
		----------------------------*/
		base.resizeNow = function(){
			
			return base.jQueryel.each(function() {
		  		//  Resize each image seperately
		  		jQuery('img', base.el).each(function(){
		  			
					thisSlide = jQuery(this);
					var ratio = (thisSlide.data('origHeight')/thisSlide.data('origWidth')).toFixed(2);	// Define image ratio
					
					// Gather browser size
					var browserwidth = base.jQueryel.width(),
						browserheight = base.jQueryel.height(),
						offset;
					
					/*-----Resize Image-----*/
					if (base.options.fit_always){	// Fit always is enabled
						if ((browserheight/browserwidth) > ratio){
							resizeWidth();
						} else {
							resizeHeight();
						}
					}else{	// Normal Resize
						if ((browserheight <= base.options.min_height) && (browserwidth <= base.options.min_width)){	// If window smaller than minimum width and height
						
							if ((browserheight/browserwidth) > ratio){
								base.options.fit_landscape && ratio < 1 ? resizeWidth(true) : resizeHeight(true);	// If landscapes are set to fit
							} else {
								base.options.fit_portrait && ratio >= 1 ? resizeHeight(true) : resizeWidth(true);		// If portraits are set to fit
							}
						
						} else if (browserwidth <= base.options.min_width){		// If window only smaller than minimum width
						
							if ((browserheight/browserwidth) > ratio){
								base.options.fit_landscape && ratio < 1 ? resizeWidth(true) : resizeHeight();	// If landscapes are set to fit
							} else {
								base.options.fit_portrait && ratio >= 1 ? resizeHeight() : resizeWidth(true);		// If portraits are set to fit
							}
							
						} else if (browserheight <= base.options.min_height){	// If window only smaller than minimum height
						
							if ((browserheight/browserwidth) > ratio){
								base.options.fit_landscape && ratio < 1 ? resizeWidth() : resizeHeight(true);	// If landscapes are set to fit
							} else {
								base.options.fit_portrait && ratio >= 1 ? resizeHeight(true) : resizeWidth();		// If portraits are set to fit
							}
						
						} else {	// If larger than minimums
							
							if ((browserheight/browserwidth) > ratio){
								base.options.fit_landscape && ratio < 1 ? resizeWidth() : resizeHeight();	// If landscapes are set to fit
							} else {
								base.options.fit_portrait && ratio >= 1 ? resizeHeight() : resizeWidth();		// If portraits are set to fit
							}
							
						}
					}
					/*-----End Image Resize-----*/
					
					
					/*-----Resize Functions-----*/
					
					function resizeWidth(minimum){
						if (minimum){	// If minimum height needs to be considered
							if(thisSlide.width() < browserwidth || thisSlide.width() < base.options.min_width ){
								if (thisSlide.width() * ratio >= base.options.min_height){
									thisSlide.width(base.options.min_width);
						    		thisSlide.height(thisSlide.width() * ratio);
						    	}else{
						    		resizeHeight();
						    	}
						    }
						}else{
							if (base.options.min_height >= browserheight && !base.options.fit_landscape){	// If minimum height needs to be considered
								if (browserwidth * ratio >= base.options.min_height || (browserwidth * ratio >= base.options.min_height && ratio <= 1)){	// If resizing would push below minimum height or image is a landscape
									thisSlide.width(browserwidth);
									thisSlide.height(browserwidth * ratio);
								} else if (ratio > 1){		// Else the image is portrait
									thisSlide.height(base.options.min_height);
									thisSlide.width(thisSlide.height() / ratio);
								} else if (thisSlide.width() < browserwidth) {
									thisSlide.width(browserwidth);
						    		thisSlide.height(thisSlide.width() * ratio);
								}
							}else{	// Otherwise, resize as normal
								thisSlide.width(browserwidth);
								thisSlide.height(browserwidth * ratio);
							}
						}
					};
					
					function resizeHeight(minimum){
						if (minimum){	// If minimum height needs to be considered
							if(thisSlide.height() < browserheight){
								if (thisSlide.height() / ratio >= base.options.min_width){
									thisSlide.height(base.options.min_height);
									thisSlide.width(thisSlide.height() / ratio);
								}else{
									resizeWidth(true);
								}
							}
						}else{	// Otherwise, resized as normal
							if (base.options.min_width >= browserwidth){	// If minimum width needs to be considered
								if (browserheight / ratio >= base.options.min_width || ratio > 1){	// If resizing would push below minimum width or image is a portrait
									thisSlide.height(browserheight);
									thisSlide.width(browserheight / ratio);
								} else if (ratio <= 1){		// Else the image is landscape
									thisSlide.width(base.options.min_width);
						    		thisSlide.height(thisSlide.width() * ratio);
								}
							}else{	// Otherwise, resize as normal
								thisSlide.height(browserheight);
								thisSlide.width(browserheight / ratio);
							}
						}
					};
					
					/*-----End Resize Functions-----*/
					
					if (thisSlide.parent().hasClass('image-loading')){
						jQuery('.image-loading').removeClass('image-loading');
					}
					
					// Horizontally Center
					if (base.options.horizontal_center){
						jQuery(this).css('left', (browserwidth - jQuery(this).width())/2);
					}
					
					// Vertically Center
					if (base.options.vertical_center){
						jQuery(this).css('top', (browserheight - jQuery(this).height())/2);
					}
					
				});
				
				// Basic image drag and right click protection
				if (base.options.image_protect){
					
					jQuery('img', base.el).bind("contextmenu mousedown",function(){
						return false;
					});
				
				}
				
				return false;
				
			});
			
		};
        
        
        /* Next Slide
		----------------------------*/
		base.nextSlide = function(){
			
			if(vars.in_animation || !api.options.slideshow) return false;		// Abort if currently animating
				else vars.in_animation = true;		// Otherwise set animation marker
		   
		    clearInterval(vars.slideshow_interval);	// Stop slideshow
		    
		    var slides = base.options.slides,					// Pull in slides array
				liveslide = base.jQueryel.find('.activeslide');		// Find active slide
				liveslide.removeClass('activeslide');			// Remove active class
			
		    if ( liveslide.length == 0 ) liveslide = base.jQueryel.find('a:last');	// If end of set, note this is last slide
		    var nextslide = liveslide.next().length ? liveslide.next() : base.jQueryel.find('a:first'),
		    	prevslide = nextslide.prev().length ? nextslide.prev() : base.jQueryel.find('a:last');
			
			// Update previous slide
			prevslide.addClass('prevslide');
			
			// Get the slide number of new slide
			vars.current_slide + 1 == base.options.slides.length ? vars.current_slide = 0 : vars.current_slide++;
			
			// If hybrid mode is on drop quality for transition
			if (base.options.performance == 1) base.jQueryel.removeClass('quality').addClass('speed');	
			
			
			/*-----Load Image-----*/
			
			loadSlide = false;
			// If links should open in new window
			var linkTarget = base.options.new_window ? ' target="_blank"' : '';
			
			vars.current_slide == base.options.slides.length - 1 ? loadSlide = 0 : loadSlide = vars.current_slide + 1;	// Determine next slide
			imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
			
			var img = jQuery('<img src="'+base.options.slides[loadSlide].image+'"/>');
			img.appendTo(base.el).wrap('<a class="image-loading" ' + imageLink + linkTarget + '></a>').css('visibility','hidden');
			
			img.load(function(){
				base._origDim(jQuery(this));
				base.resizeNow();
			});	// End Load
			
			// Update thumbnails (if enabled)
			if (base.options.thumbnail_navigation == 1){
			
				// Load previous thumbnail
				vars.current_slide - 1 < 0  ? prevThumb = base.options.slides.length - 1 : prevThumb = vars.current_slide - 1;
				jQuery(vars.prev_thumb).html(jQuery("<img/>").attr("src", base.options.slides[prevThumb].image));
			
				// Load next thumbnail
				nextThumb = loadSlide;
				jQuery(vars.next_thumb).html(jQuery("<img/>").attr("src", base.options.slides[nextThumb].image));
				
			}
			
			liveslide.prev().remove(); // Remove Old Image
			
			/*-----End Load Image-----*/
			
			
			// Call theme function for before slide transition
			if( typeof theme != 'undefined' && typeof theme.beforeAnimation == "function" ) theme.beforeAnimation('next');
			
			//Update slide markers
			if (base.options.slide_links){
				jQuery('.current-slide').removeClass('current-slide');
				jQuery('li', vars.slide_list).eq(vars.current_slide).addClass('current-slide');
			}
		    
		    nextslide.css('visibility','hidden').addClass('activeslide');	// Update active slide
		    
	    	switch(base.options.transition){
	    		case 0: case 'none':	// No transition
	    		    nextslide.css('visibility','visible');
	    		    vars.in_animation = false;
	    		    break;
	    		case 1: case 'fade':	// Fade
	    		    nextslide.animate({opacity : 0},0).css('visibility','visible').animate({opacity : 1, avoidTransforms : false}, base.options.transition_speed, function(){ base.afterAnimation(); });
	    		    break;
	    		case 2: case 'slideTop':	// Slide Top
	    		    nextslide.animate({top : -base.jQueryel.height()}, 0 ).css('visibility','visible').animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    		    break;
	    		case 3: case 'slideRight':	// Slide Right
	    			nextslide.animate({left : base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    			break;
	    		case 4: case 'slideBottom': // Slide Bottom
	    			nextslide.animate({top : base.jQueryel.height()}, 0 ).css('visibility','visible').animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    			break;
	    		case 5: case 'slideLeft':  // Slide Left
	    			nextslide.animate({left : -base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    			break;
	    		case 6: case 'carouselRight':	// Carousel Right
	    			nextslide.animate({left : base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
					liveslide.animate({ left: -base.jQueryel.width(), avoidTransforms : false }, base.options.transition_speed );
	    			break;
	    		case 7: case 'carouselLeft':   // Carousel Left
	    			nextslide.animate({left : -base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
					liveslide.animate({ left: base.jQueryel.width(), avoidTransforms : false }, base.options.transition_speed );
	    			break;
	    	}
		    return false;	
		};
		
		
		/* Previous Slide
		----------------------------*/
		base.prevSlide = function(){
		
			if(vars.in_animation || !api.options.slideshow) return false;		// Abort if currently animating
				else vars.in_animation = true;		// Otherwise set animation marker
			
			clearInterval(vars.slideshow_interval);	// Stop slideshow
			
			var slides = base.options.slides,					// Pull in slides array
				liveslide = base.jQueryel.find('.activeslide');		// Find active slide
				liveslide.removeClass('activeslide');			// Remove active class
			
		    if ( liveslide.length == 0 ) liveslide = base.jQueryel.find('a:first');	// If end of set, note this is first slide
		    var nextslide =  liveslide.prev().length ? liveslide.prev() : base.jQueryel.find('a:last'),
		    	prevslide =  nextslide.next().length ? nextslide.next() : base.jQueryel.find('a:first');
			
			// Update previous slide
			prevslide.addClass('prevslide');
					
			// Get current slide number
			vars.current_slide == 0 ?  vars.current_slide = base.options.slides.length - 1 : vars.current_slide-- ;
			
			// If hybrid mode is on drop quality for transition
			if (base.options.performance == 1) base.jQueryel.removeClass('quality').addClass('speed');	
			
			
			/*-----Load Image-----*/
			
			loadSlide = false;
			// If links should open in new window
			var linkTarget = base.options.new_window ? ' target="_blank"' : '';
			
			vars.current_slide - 1 < 0  ? loadSlide = base.options.slides.length - 1 : loadSlide = vars.current_slide - 1;	// Determine next slide
			imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
			
			var img = jQuery('<img src="'+base.options.slides[loadSlide].image+'"/>');
			img.prependTo(base.el).wrap('<a class="image-loading" ' + imageLink + linkTarget + '></a>').css('visibility','hidden');
			
			img.load(function(){
				base._origDim(jQuery(this));
				base.resizeNow();
			});	// End Load
			
			// Update thumbnails (if enabled)
			if (base.options.thumbnail_navigation == 1){
			
				// Load previous thumbnail
				prevThumb = loadSlide;
				jQuery(vars.prev_thumb).html(jQuery("<img/>").attr("src", base.options.slides[prevThumb].image));
				
				// Load next thumbnail
				vars.current_slide == base.options.slides.length - 1 ? nextThumb = 0 : nextThumb = vars.current_slide + 1;
				jQuery(vars.next_thumb).html(jQuery("<img/>").attr("src", base.options.slides[nextThumb].image));
			}
			
			liveslide.next().remove(); // Remove Old Image
			
			/*-----End Load Image-----*/
			
			
			// Call theme function for before slide transition
			if( typeof theme != 'undefined' && typeof theme.beforeAnimation == "function" ) theme.beforeAnimation('prev');
			
			//Update slide markers
			if (base.options.slide_links){
				jQuery('.current-slide').removeClass('current-slide');
				jQuery('li', vars.slide_list).eq(vars.current_slide).addClass('current-slide');
			}
			
		    nextslide.css('visibility','hidden').addClass('activeslide');	// Update active slide
		    
		    switch(base.options.transition){
	    		case 0: case 'none':	// No transition
	    		    nextslide.css('visibility','visible'); vars.in_animation = false; base.afterAnimation();
	    		    break;
	    		case 1: case 'fade':	// Fade
	    		  	nextslide.animate({opacity : 0},0).css('visibility','visible').animate({opacity : 1, avoidTransforms : false}, base.options.transition_speed, function(){ base.afterAnimation(); });
	    		    break;
	    		case 2: case 'slideTop':	// Slide Top (reverse)
	    		    nextslide.animate({top : base.jQueryel.height()}, 0 ).css('visibility','visible').animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    		    break;
	    		case 3: case 'slideRight':	// Slide Right (reverse)
	    			nextslide.animate({left : -base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    			break;
	    		case 4: case 'slideBottom': // Slide Bottom (reverse)
	    			nextslide.animate({top : -base.jQueryel.height()}, 0 ).css('visibility','visible').animate({ top:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    			break;
	    		case 5: case 'slideLeft':  // Slide Left (reverse)
	    			nextslide.animate({left : base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
	    			break;
	    		case 6: case 'carouselRight':	// Carousel Right (reverse)
	    			nextslide.animate({left : -base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
					liveslide.animate({left : 0}, 0 ).animate({ left: base.jQueryel.width(), avoidTransforms : false}, base.options.transition_speed );
	    			break;
	    		case 7: case 'carouselLeft':   // Carousel Left (reverse)
	    			nextslide.animate({left : base.jQueryel.width()}, 0 ).css('visibility','visible').animate({ left:0, avoidTransforms : false }, base.options.transition_speed, function(){ base.afterAnimation(); });
					liveslide.animate({left : 0}, 0 ).animate({ left: -base.jQueryel.width(), avoidTransforms : false }, base.options.transition_speed );
	    			break;
	    	}
		    return false;	
		};
		
		
		/* Play/Pause Toggle
		----------------------------*/
		base.playToggle = function(){
		
			if (vars.in_animation || !api.options.slideshow) return false;		// Abort if currently animating
			
			if (vars.is_paused){
				
				vars.is_paused = false;
				
				// Call theme function for play
				if( typeof theme != 'undefined' && typeof theme.playToggle == "function" ) theme.playToggle('play');
				
				// Resume slideshow
	        	vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
	        	  
        	}else{
        		
        		vars.is_paused = true;
        		
        		// Call theme function for pause
        		if( typeof theme != 'undefined' && typeof theme.playToggle == "function" ) theme.playToggle('pause');
        		
        		// Stop slideshow
        		clearInterval(vars.slideshow_interval);	
       		
       		}
		    
		    return false;
    		
    	};
    	
    	
    	/* Go to specific slide
		----------------------------*/
    	base.goTo = function(targetSlide){
    			
			if (vars.in_animation || !api.options.slideshow) return false;		// Abort if currently animating
			
			var totalSlides = base.options.slides.length;
			vars.update_images = true;
			
			// If target outside range
			if(targetSlide < 0){
				targetSlide = totalSlides;
			}else if(targetSlide > totalSlides){
				targetSlide = 1;
			}
			targetSlide = totalSlides - targetSlide + 1;
			
			clearInterval(vars.slideshow_interval);	// Stop slideshow, prevent buildup
			
			// Call theme function for goTo trigger
			if (typeof theme != 'undefined' && typeof theme.goTo == "function" ) theme.goTo();
			
			if (vars.current_slide == totalSlides - targetSlide){
				if(!(vars.is_paused)){
					vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
				} 
				return false;
			}
			
			// If ahead of current position
			if(totalSlides - targetSlide > vars.current_slide ){
				
				// Adjust for new next slide
				vars.current_slide = totalSlides-targetSlide-1;
				base._placeSlide('next');
				
			//Otherwise it's before current position
			}else if(totalSlides - targetSlide < vars.current_slide){
				
				// Adjust for new prev slide
				vars.current_slide = totalSlides-targetSlide+1;
			    base._placeSlide('prev');
			    
			}
			
			// set active markers
			if (base.options.slide_links){
				jQuery('.current-slide', vars.slide_list).removeClass('current-slide');
				jQuery('li', vars.slide_list).eq((totalSlides-targetSlide)).addClass('current-slide');
			}
			
			if (base.options.thumb_links){
				jQuery('.current-thumb', vars.thumb_list).removeClass('current-thumb');
				jQuery('li', vars.thumb_list).eq((totalSlides-targetSlide)).addClass('current-thumb');
			}
			
		};
        
        
        /* Place Slide
		----------------------------*/
        base._placeSlide = function(place){
    			
			// If links should open in new window
			var linkTarget = base.options.new_window ? ' target="_blank"' : '';
			
			if (place == 'next'){
			
				// Remove slide to be replaced
				jQuery('.activeslide').next().remove();
				
				loadSlide = false;
				vars.current_slide == base.options.slides.length - 1 ? loadSlide = 0 : loadSlide = vars.current_slide + 1;	// Determine next slide
				imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
				
				var img = jQuery('<img src="'+base.options.slides[loadSlide].image+'"/>');
				img.appendTo(base.el).wrap('<a class="image-loading" ' + imageLink + linkTarget + '></a>').css('visibility','hidden');
				
				if (vars.update_images) base.nextSlide();		
				
				img.load(function(){
					base._origDim(jQuery(this));
					base.resizeNow();
				});	// End Load
				
			}else if (place == 'prev'){
			
				// Remove slide to be replaced
				jQuery('.activeslide').prev().remove();
				
				loadSlide = false;
			
				vars.current_slide - 1 < 0  ? loadSlide = base.options.slides.length - 1 : loadSlide = vars.current_slide - 1;	// Determine next slide
				imageLink = (base.options.slides[loadSlide].url) ? "href='" + base.options.slides[loadSlide].url + "'" : "";	// If link exists, build it
				
				var img = jQuery('<img src="'+base.options.slides[loadSlide].image+'"/>');
				img.prependTo(base.el).wrap('<a class="image-loading" ' + imageLink + linkTarget + '></a>').css('visibility','hidden');
				
				
			    if (vars.update_images) base.prevSlide();
				
				img.load(function(){
					base._origDim(jQuery(this));
			    	base.resizeNow();
				});	// End Load
			}
			
		};
		
		
		/* Get Original Dimensions
		----------------------------*/
		base._origDim = function(targetSlide){
			targetSlide.data('origWidth', targetSlide.width()).data('origHeight', targetSlide.height()).css('visibility','visible');
		};
		
		
		/* After Slide Animation
		----------------------------*/
		base.afterAnimation = function(){
			
			// If hybrid mode is on swap back to higher image quality
			if (base.options.performance == 1){
		    	base.jQueryel.removeClass('speed').addClass('quality');
			}
			
			if (vars.update_images){
				vars.update_images = false;
				base._placeSlide('next');
				base._placeSlide('prev');
			}
			
			vars.in_animation = false;
			
			// Resume slideshow
			if (!vars.is_paused && base.options.slideshow){
				vars.slideshow_interval = setInterval(base.nextSlide, base.options.slide_interval);
				if (base.options.stop_loop && vars.current_slide == base.options.slides.length - 1 ) base.playToggle();
			}
			
			
			// Call theme function for after slide transition
			if (typeof theme != 'undefined' && typeof theme.afterAnimation == "function" ) theme.afterAnimation();
			
			return false;
		
		};
		
		base.getField = function(field){
			return base.options.slides[vars.current_slide][field];
		};
		
        // Make it go!
        base.init();
	};
	
	
	/* Global Variables
	----------------------------*/
	jQuery.supersized.vars = {
	
		// Elements							
		thumb_tray			:	'#thumb-tray',	//Thumbnail tray
		thumb_list			:	'#thumb-list',	//Thumbnail list
		
		// Internal variables
		current_slide			:	0,			// Current slide number
		in_animation 			:	false,		// Prevents animations from stacking
		is_paused 				: 	false,		// Tracks paused on/off
		hover_pause				:	false,		// If slideshow is paused from hover
		slideshow_interval		:	false,		// Stores slideshow timer					
		update_images 			: 	false,		// Trigger to update images after slide jump
		options					:	{}			// Stores assembled options list
		
	};
	
	
	/* Default Options
	----------------------------*/
	jQuery.supersized.defaultOptions = {
    
    	// Functionality
		slideshow               :   1,			// Slideshow on/off
		autoplay				:	1,			// Slideshow starts playing automatically
		start_slide             :   1,			// Start slide (0 is random)
		stop_loop				:	0,			// Stops slideshow on last slide
		random					: 	0,			// Randomize slide order (Ignores start slide)
		slide_interval          :   5000,		// Length between transitions
		transition              :   1, 			// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
		transition_speed		:	750,		// Speed of transition
		new_window				:	1,			// Image links open in new window/tab
		pause_hover             :   0,			// Pause slideshow on hover
		keyboard_nav            :   1,			// Keyboard navigation on/off
		performance				:	1,			// 0-Normal, 1-Hybrid speed/quality, 2-Optimizes image quality, 3-Optimizes transition speed //  (Only works for Firefox/IE, not Webkit)
		image_protect			:	1,			// Disables image dragging and right click with Javascript
												   
		// Size & Position
		fit_always				:	0,			// Image will never exceed browser width or height (Ignores min. dimensions)
		fit_landscape			:   0,			// Landscape images will not exceed browser width
		fit_portrait         	:   1,			// Portrait images will not exceed browser height  			   
		min_width		        :   0,			// Min width allowed (in pixels)
		min_height		        :   0,			// Min height allowed (in pixels)
		horizontal_center       :   1,			// Horizontally center background
		vertical_center         :   1,			// Vertically center background
		
												   
		// Components							
		slide_links				:	1,			// Individual links for each slide	
		thumb_links				:	1,			// Individual thumb links for each slide
		show_bottombar			:	1,			// show bottom bar
		show_prevnextbutton		:	1,			// show previous and next button
		show_slidecounter		:	0,			// show slide counter
		show_imagetitle			:	1,			// show image title
		show_playbutton			:	1,			// show play button
		show_progressbar		:	1,			// show progress bar
		thumbnail_navigation    :   0			// Thumbnail navigation
    	
    };
    
    jQuery.fn.supersized = function(options){
        return this.each(function(){
            (new jQuery.supersized(options));
        });
    };
		
})(jQuery);
