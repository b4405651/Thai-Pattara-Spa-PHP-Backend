// (c)2015 ClOuD StuDiO

jQuery.noConflict();(function(jQuery){theme={_init:function(){if(api.options.slide_links)jQuery(vars.slide_list).css('margin-left',-jQuery(vars.slide_list).width()/2);if(api.options.autoplay){if(api.options.progress_bar)theme.progressBar();}else{if(jQuery(vars.play_button).attr('src'))jQuery(vars.play_button).attr("src",vars.image_path+"play.png");if(api.options.progress_bar)jQuery(vars.progress_bar).stop().animate({left:-jQuery(window).width()},0);}
jQuery(vars.thumb_tray).animate({bottom:-jQuery(vars.thumb_tray).height()},0);jQuery(vars.tray_button).toggle(function(){jQuery(vars.thumb_tray).stop().animate({bottom:-jQuery(vars.thumb_tray).height(),avoidTransforms:true},300);if(jQuery(vars.tray_arrow).toggleClass('full-screen-button-arrow'));return false;},function(){jQuery(vars.thumb_tray).stop().animate({bottom:0,avoidTransforms:true},300);if(jQuery(vars.tray_arrow).toggleClass('full-screen-button-arrow'));return false;});jQuery(vars.thumb_list).width(jQuery('> li',vars.thumb_list).length*jQuery('> li',vars.thumb_list).outerWidth(true));if(jQuery(vars.slide_total).length){jQuery(vars.slide_total).html(api.options.slides.length);}
if(api.options.thumb_links){if(jQuery(vars.thumb_list).width()<=jQuery(vars.thumb_tray).width()){jQuery(vars.thumb_back+','+vars.thumb_forward).fadeOut(0);}
vars.thumb_interval=Math.floor(jQuery(vars.thumb_tray).width()/jQuery('> li',vars.thumb_list).outerWidth(true))*jQuery('> li',vars.thumb_list).outerWidth(true);vars.thumb_page=0;jQuery(vars.thumb_forward).click(function(){if(vars.thumb_page-vars.thumb_interval<=-jQuery(vars.thumb_list).width()){vars.thumb_page=0;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}else{vars.thumb_page=vars.thumb_page-vars.thumb_interval;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}});jQuery(vars.thumb_back).click(function(){if(vars.thumb_page+vars.thumb_interval>0){vars.thumb_page=Math.floor(jQuery(vars.thumb_list).width()/vars.thumb_interval)*-vars.thumb_interval;if(jQuery(vars.thumb_list).width()<=-vars.thumb_page)vars.thumb_page=vars.thumb_page+vars.thumb_interval;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}else{vars.thumb_page=vars.thumb_page+vars.thumb_interval;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}});}
jQuery(vars.next_slide).click(function(){api.nextSlide();});jQuery(vars.prev_slide).click(function(){api.prevSlide();});if(jQuery.support.opacity){jQuery(vars.prev_slide+','+vars.next_slide).mouseover(function(){jQuery(this).stop().animate({opacity:0.7},100);}).mouseout(function(){jQuery(this).stop().animate({opacity:0.3},100);});}
if(api.options.thumbnail_navigation){jQuery(vars.next_thumb).click(function(){api.nextSlide();});jQuery(vars.prev_thumb).click(function(){api.prevSlide();});}
jQuery(vars.play_button).click(function(){api.playToggle();});if(api.options.mouse_scrub){jQuery(vars.thumb_tray).mousemove(function(e){var containerWidth=jQuery(vars.thumb_tray).width(),listWidth=jQuery(vars.thumb_list).width();if(listWidth>containerWidth){var mousePos=1,diff=e.pageX-mousePos;if(diff>10||diff<-10){mousePos=e.pageX;newX=(containerWidth-listWidth)*(e.pageX/containerWidth);diff=parseInt(Math.abs(parseInt(jQuery(vars.thumb_list).css('left'))-newX)).toFixed(0);jQuery(vars.thumb_list).stop().animate({'left':newX},{duration:diff*3,easing:'linear'});}}});}
jQuery(window).resize(function(){if(api.options.progress_bar&&!vars.in_animation){if(vars.slideshow_interval)clearInterval(vars.slideshow_interval);if(api.options.slides.length-1>0)clearInterval(vars.slideshow_interval);jQuery(vars.progress_bar).stop().animate({left:-jQuery(window).width()},0);if(!vars.progressDelay&&api.options.slideshow){vars.progressDelay=setTimeout(function(){if(!vars.is_paused){theme.progressBar();vars.slideshow_interval=setInterval(api.nextSlide,api.options.slide_interval);}
vars.progressDelay=false;},1000);}}
if(api.options.thumb_links&&vars.thumb_tray.length){vars.thumb_page=0;vars.thumb_interval=Math.floor(jQuery(vars.thumb_tray).width()/jQuery('> li',vars.thumb_list).outerWidth(true))*jQuery('> li',vars.thumb_list).outerWidth(true);if(jQuery(vars.thumb_list).width()>jQuery(vars.thumb_tray).width()){jQuery(vars.thumb_back+','+vars.thumb_forward).fadeIn('fast');jQuery(vars.thumb_list).stop().animate({'left':0},200);}else{jQuery(vars.thumb_back+','+vars.thumb_forward).fadeOut('fast');}}});},goTo:function(){if(api.options.progress_bar&&!vars.is_paused){jQuery(vars.progress_bar).stop().animate({left:-jQuery(window).width()},0);theme.progressBar();}},playToggle:function(state){if(state=='play'){if(jQuery(vars.play_button).toggleClass('full-screen-button-play'));if(api.options.progress_bar&&!vars.is_paused)theme.progressBar();}else if(state=='pause'){if(jQuery(vars.play_button).toggleClass('full-screen-button-play'));if(api.options.progress_bar&&vars.is_paused)jQuery(vars.progress_bar).stop().animate({left:-jQuery(window).width()},0);}},beforeAnimation:function(direction){if(api.options.progress_bar&&!vars.is_paused)jQuery(vars.progress_bar).stop().animate({left:-jQuery(window).width()},0);if(jQuery(vars.slide_caption).length){(api.getField('title'))?jQuery(vars.slide_caption).html(api.getField('title')):jQuery(vars.slide_caption).html('');}
if(vars.slide_current.length){jQuery(vars.slide_current).html(vars.current_slide+1);}
if(api.options.thumb_links){jQuery('.current-thumb').removeClass('current-thumb');jQuery('li',vars.thumb_list).eq(vars.current_slide).addClass('current-thumb');if(jQuery(vars.thumb_list).width()>jQuery(vars.thumb_tray).width()){if(direction=='next'){if(vars.current_slide==0){vars.thumb_page=0;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}else if(jQuery('.current-thumb').offset().left-jQuery(vars.thumb_tray).offset().left>=vars.thumb_interval){vars.thumb_page=vars.thumb_page-vars.thumb_interval;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}}else if(direction=='prev'){if(vars.current_slide==api.options.slides.length-1){vars.thumb_page=Math.floor(jQuery(vars.thumb_list).width()/vars.thumb_interval)*-vars.thumb_interval;if(jQuery(vars.thumb_list).width()<=-vars.thumb_page)vars.thumb_page=vars.thumb_page+vars.thumb_interval;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}else if(jQuery('.current-thumb').offset().left-jQuery(vars.thumb_tray).offset().left<0){if(vars.thumb_page+vars.thumb_interval>0)return false;vars.thumb_page=vars.thumb_page+vars.thumb_interval;jQuery(vars.thumb_list).stop().animate({'left':vars.thumb_page},{duration:500,easing:'linear'});}}}}},afterAnimation:function(){if(api.options.progress_bar&&!vars.is_paused)theme.progressBar();},progressBar:function(){jQuery(vars.progress_bar).stop().animate({left:-jQuery(window).width()},0).animate({left:0},api.options.slide_interval);}};jQuery.supersized.themeVars={progress_delay:false,thumb_page:false,thumb_interval:false,image_path:'templates/full_screen_4/images/supersized/',play_button:'#pauseplay',next_slide:'#nextslide',prev_slide:'#prevslide',next_thumb:'#nextthumb',prev_thumb:'#prevthumb',slide_caption:'#slidecaption',slide_current:'.slidenumber',slide_total:'.totalslides',slide_list:'#slide-list',thumb_tray:'#thumb-tray',thumb_list:'#thumb-list',thumb_forward:'#thumb-forward',thumb_back:'#thumb-back',tray_arrow:'#tray-arrow',tray_button:'#tray-button',progress_bar:'#progress-bar'};jQuery.supersized.themeOptions={progress_bar:1,mouse_scrub:0};})(jQuery);