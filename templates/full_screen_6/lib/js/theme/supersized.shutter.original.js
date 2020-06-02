/*

	Supersized - Fullscreen Slideshow jQuery Plugin
	Version : 3.2.0
	Theme 	: Shutter
	
	Site	: www.buildinternet.com/project/supersized
	Author	: Sam Dunn
	Company : One Mighty Roar (www.onemightyroar.com)
	License : MIT License / GPL License

*/

jQuery.noConflict();

(function(jQuery){
	
	theme = {
	 	
	 	
	 	/* Initial Placement
		----------------------------*/
	 	_init : function(){
	 		
	 		// Center Slide Links
	 		if (api.options.slide_links) jQuery(vars.slide_list).css('margin-left', -jQuery(vars.slide_list).width()/2);
	 		
			// Start progressbar if autoplay enabled
    		if (api.options.autoplay){
    			if (api.options.progress_bar) theme.progressBar();
			}else{
				if (jQuery(vars.play_button).attr('src')) jQuery(vars.play_button).attr("src", vars.image_path + "play.png");	// If pause play button is image, swap src
				if (api.options.progress_bar) jQuery(vars.progress_bar).stop().animate({left : -jQuery(window).width()}, 0 );	//  Place progress bar
			}
			
			
			/* Thumbnail Tray
			----------------------------*/
			// Hide tray off screen
			jQuery(vars.thumb_tray).animate({bottom : -jQuery(vars.thumb_tray).height()}, 0 );
			
			// Thumbnail Tray Toggle
			jQuery(vars.tray_button).toggle(function(){
				jQuery(vars.thumb_tray).stop().animate({bottom : -jQuery(vars.thumb_tray).height(), avoidTransforms : true}, 300 );
				if (jQuery(vars.tray_arrow).toggleClass('full-screen-button-arrow'));
				return false;
				
			}, function() {
				jQuery(vars.thumb_tray).stop().animate({bottom : 0, avoidTransforms : true}, 300 );
				if (jQuery(vars.tray_arrow).toggleClass('full-screen-button-arrow'));
				return false;
			
			
				
			});
			
			// Make thumb tray proper size
			jQuery(vars.thumb_list).width(jQuery('> li', vars.thumb_list).length * jQuery('> li', vars.thumb_list).outerWidth(true));	//Adjust to true width of thumb markers
			
			// Display total slides
			if (jQuery(vars.slide_total).length){
				jQuery(vars.slide_total).html(api.options.slides.length);
			}
			
			
			/* Thumbnail Tray Navigation
			----------------------------*/	
			if (api.options.thumb_links){
				//Hide thumb arrows if not needed
				if (jQuery(vars.thumb_list).width() <= jQuery(vars.thumb_tray).width()){
					jQuery(vars.thumb_back +','+vars.thumb_forward).fadeOut(0);
				}
				
				// Thumb Intervals
        		vars.thumb_interval = Math.floor(jQuery(vars.thumb_tray).width() / jQuery('> li', vars.thumb_list).outerWidth(true)) * jQuery('> li', vars.thumb_list).outerWidth(true);
        		vars.thumb_page = 0;
        		
        		// Cycle thumbs forward
        		jQuery(vars.thumb_forward).click(function(){
        			if (vars.thumb_page - vars.thumb_interval <= -jQuery(vars.thumb_list).width()){
        				vars.thumb_page = 0;
        				jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
        			}else{
        				vars.thumb_page = vars.thumb_page - vars.thumb_interval;
        				jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
        			}
        		});
        		
        		// Cycle thumbs backwards
        		jQuery(vars.thumb_back).click(function(){
        			if (vars.thumb_page + vars.thumb_interval > 0){
        				vars.thumb_page = Math.floor(jQuery(vars.thumb_list).width() / vars.thumb_interval) * -vars.thumb_interval;
        				if (jQuery(vars.thumb_list).width() <= -vars.thumb_page) vars.thumb_page = vars.thumb_page + vars.thumb_interval;
        				jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
					}else{
        				vars.thumb_page = vars.thumb_page + vars.thumb_interval;
        				jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
        			}
        		});
				
			}
			
			
			/* Navigation Items
			----------------------------*/
		    jQuery(vars.next_slide).click(function() {
		    	api.nextSlide();
		    });
		    
		    jQuery(vars.prev_slide).click(function() {
		    	api.prevSlide();
		    });
		    
		    	// Full Opacity on Hover
		    	if(jQuery.support.opacity){
			    	jQuery(vars.prev_slide +','+vars.next_slide).mouseover(function() {
					   jQuery(this).stop().animate({opacity:0.7},100);
					}).mouseout(function(){
					   jQuery(this).stop().animate({opacity:0.3},100);
					});
				}
			
			if (api.options.thumbnail_navigation){
				// Next thumbnail clicked
				jQuery(vars.next_thumb).click(function() {
			    	api.nextSlide();
			    });
			    // Previous thumbnail clicked
			    jQuery(vars.prev_thumb).click(function() {
			    	api.prevSlide();
			    });
			}
			
		    jQuery(vars.play_button).click(function() {
				api.playToggle();						    
		    });
			
			
			/* Thumbnail Mouse Scrub
			----------------------------*/
    		if (api.options.mouse_scrub){
				jQuery(vars.thumb_tray).mousemove(function(e) {
					var containerWidth = jQuery(vars.thumb_tray).width(),
						listWidth = jQuery(vars.thumb_list).width();
					if (listWidth > containerWidth){
						var mousePos = 1,
							diff = e.pageX - mousePos;
						if (diff > 10 || diff < -10) { 
						    mousePos = e.pageX; 
						    newX = (containerWidth - listWidth) * (e.pageX/containerWidth);
						    diff = parseInt(Math.abs(parseInt(jQuery(vars.thumb_list).css('left'))-newX )).toFixed(0);
						    jQuery(vars.thumb_list).stop().animate({'left':newX}, {duration:diff*3, easing:'linear'});
						}
					}
				});
			}
			
			
			/* Window Resize
			----------------------------*/
			jQuery(window).resize(function(){
				
				// Delay progress bar on resize
				if (api.options.progress_bar && !vars.in_animation){
					if (vars.slideshow_interval) clearInterval(vars.slideshow_interval);
					if (api.options.slides.length - 1 > 0) clearInterval(vars.slideshow_interval);
					
					jQuery(vars.progress_bar).stop().animate({left : -jQuery(window).width()}, 0 );
					
					if (!vars.progressDelay && api.options.slideshow){
						// Delay slideshow from resuming so Chrome can refocus images
						vars.progressDelay = setTimeout(function() {
								if (!vars.is_paused){
									theme.progressBar();
									vars.slideshow_interval = setInterval(api.nextSlide, api.options.slide_interval);
								}
								vars.progressDelay = false;
						}, 1000);
					}
				}
				
				// Thumb Links
				if (api.options.thumb_links && vars.thumb_tray.length){
					// Update Thumb Interval & Page
					vars.thumb_page = 0;	
					vars.thumb_interval = Math.floor(jQuery(vars.thumb_tray).width() / jQuery('> li', vars.thumb_list).outerWidth(true)) * jQuery('> li', vars.thumb_list).outerWidth(true);
					
					// Adjust thumbnail markers
					if (jQuery(vars.thumb_list).width() > jQuery(vars.thumb_tray).width()){
						jQuery(vars.thumb_back +','+vars.thumb_forward).fadeIn('fast');
						jQuery(vars.thumb_list).stop().animate({'left':0}, 200);
					}else{
						jQuery(vars.thumb_back +','+vars.thumb_forward).fadeOut('fast');
					}
					
				}
			});	
			
								
	 	},
	 	
	 	
	 	/* Go To Slide
		----------------------------*/
	 	goTo : function(){
	 		if (api.options.progress_bar && !vars.is_paused){
				jQuery(vars.progress_bar).stop().animate({left : -jQuery(window).width()}, 0 );
				theme.progressBar();
			}
		},
	 	
	 	/* Play & Pause Toggle
		----------------------------*/
	 	playToggle : function(state){
	 		
	 		if (state =='play'){
	 			// If image, swap to pause
	 			if (jQuery(vars.play_button).toggleClass('full-screen-button-play'));
				if (api.options.progress_bar && !vars.is_paused) theme.progressBar();
	 		}else if (state == 'pause'){
	 			// If image, swap to play
	 			if (jQuery(vars.play_button).toggleClass('full-screen-button-play'));
        		if (api.options.progress_bar && vars.is_paused)jQuery(vars.progress_bar).stop().animate({left : -jQuery(window).width()}, 0 );
	 		}
	 		
	 	},
	 	
	 	
	 	/* Before Slide Transition
		----------------------------*/
	 	beforeAnimation : function(direction){
		    if (api.options.progress_bar && !vars.is_paused) jQuery(vars.progress_bar).stop().animate({left : -jQuery(window).width()}, 0 );
		  	
		  	/* Update Fields
		  	----------------------------*/
		  	// Update slide caption
		   	if (jQuery(vars.slide_caption).length){
		   		(api.getField('title')) ? jQuery(vars.slide_caption).html(api.getField('title')) : jQuery(vars.slide_caption).html('');
		   	}
		    // Update slide number
			if (vars.slide_current.length){
			    jQuery(vars.slide_current).html(vars.current_slide + 1);
			}
		    
		    
		    // Highlight current thumbnail and adjust row position
		    if (api.options.thumb_links){
		    
				jQuery('.current-thumb').removeClass('current-thumb');
				jQuery('li', vars.thumb_list).eq(vars.current_slide).addClass('current-thumb');
				
				// If thumb out of view
				if (jQuery(vars.thumb_list).width() > jQuery(vars.thumb_tray).width()){
					// If next slide direction
					if (direction == 'next'){
						if (vars.current_slide == 0){
							vars.thumb_page = 0;
							jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
						} else if (jQuery('.current-thumb').offset().left - jQuery(vars.thumb_tray).offset().left >= vars.thumb_interval){
	        				vars.thumb_page = vars.thumb_page - vars.thumb_interval;
	        				jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
						}
					// If previous slide direction
					}else if(direction == 'prev'){
						if (vars.current_slide == api.options.slides.length - 1){
							vars.thumb_page = Math.floor(jQuery(vars.thumb_list).width() / vars.thumb_interval) * -vars.thumb_interval;
							if (jQuery(vars.thumb_list).width() <= -vars.thumb_page) vars.thumb_page = vars.thumb_page + vars.thumb_interval;
							jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
						} else if (jQuery('.current-thumb').offset().left - jQuery(vars.thumb_tray).offset().left < 0){
							if (vars.thumb_page + vars.thumb_interval > 0) return false;
	        				vars.thumb_page = vars.thumb_page + vars.thumb_interval;
	        				jQuery(vars.thumb_list).stop().animate({'left': vars.thumb_page}, {duration:500, easing:'linear'});
						}
					}
				}
				
				
			}
		    
	 	},
	 	
	 	
	 	/* After Slide Transition
		----------------------------*/
	 	afterAnimation : function(){
	 		if (api.options.progress_bar && !vars.is_paused) theme.progressBar();	//  Start progress bar
	 	},
	 	
	 	
	 	/* Progress Bar
		----------------------------*/
		progressBar : function(){
    		jQuery(vars.progress_bar).stop().animate({left : -jQuery(window).width()}, 0 ).animate({ left:0 }, api.options.slide_interval);
    	}
	 	
	 
	 };
	 
	 
	 /* Theme Specific Variables
	 ----------------------------*/
	 jQuery.supersized.themeVars = {
	 	
	 	// Internal Variables
		progress_delay		:	false,				// Delay after resize before resuming slideshow
		thumb_page 			: 	false,				// Thumbnail page
		thumb_interval 		: 	false,				// Thumbnail interval
		image_path			:	'templates/full_screen_4/images/supersized/',				// Default image path
													
		// General Elements							
		play_button			:	'#pauseplay',		// Play/Pause button
		next_slide			:	'#nextslide',		// Next slide button
		prev_slide			:	'#prevslide',		// Prev slide button
		next_thumb			:	'#nextthumb',		// Next slide thumb button
		prev_thumb			:	'#prevthumb',		// Prev slide thumb button
		
		slide_caption		:	'#slidecaption',	// Slide caption
		slide_current		:	'.slidenumber',		// Current slide number
		slide_total			:	'.totalslides',		// Total Slides
		slide_list			:	'#slide-list',		// Slide jump list							
		
		thumb_tray			:	'#thumb-tray',		// Thumbnail tray
		thumb_list			:	'#thumb-list',		// Thumbnail list
		thumb_forward		:	'#thumb-forward',	// Cycles forward through thumbnail list
		thumb_back			:	'#thumb-back',		// Cycles backwards through thumbnail list
		tray_arrow			:	'#tray-arrow',		// Thumbnail tray button arrow
		tray_button			:	'#tray-button',		// Thumbnail tray button
		
		progress_bar		:	'#progress-bar'		// Progress bar
	 												
	 };												
	
	 /* Theme Specific Options
	 ----------------------------*/												
	 jQuery.supersized.themeOptions = {					
	 						   
		progress_bar		:	1,		// Timer for each slide											
		mouse_scrub			:	0		// Thumbnails move with mouse
		
	 };
	
	
})(jQuery);