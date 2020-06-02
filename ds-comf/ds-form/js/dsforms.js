/*--------------------
* Universal Form Builder
* @version 3.0
* @author Sergey Aleksandrov <malegender@mail.ru>
* @author Egor Bulychev <egor@bulychev.info>
* Using Reveal: jQuery Modals Made Easy
-------------------------*/
if(!window.dsC) {
	if(window.jQuery && !compareVersionjQuery('1.7') || !window.jQuery) {
		console.error('Version jQuery < 1.7 or jQuery not found!');
	} else {
		dsC = window.jQuery;
		rundsForm();
	}
} else rundsForm();
function compareVersionjQuery(needjQuery) {
	needjQuery = needjQuery.split('.',2);
	nowjQuery = jQuery.fn.jquery.split('.',2);
	if((parseInt(needjQuery[0]) != parseInt(nowjQuery[0])) || (parseInt(needjQuery[1]) > parseInt(nowjQuery[1]))) {
		return false;
	}
	return true;
}

function rundsForm() {
	dsC(document).ready(function() {
		var styleforms = document.createElement('link');
		styleforms.rel = "stylesheet";
		styleforms.href = "/ds-comf/ds-form/formscss.php";
		document.body.appendChild(styleforms);
		dsC('body').on('submit', '.ds-form form', function(){
		var formid =  dsC(this).parents('.ds-form').attr("id");
		dsC('#' + formid + ' input[type="submit"]').hide();
		dsC('#' + formid + ' img.loadform').show();
		if (!window.FormData) {
		var dataform = dsC(this).serialize();
			dataform = dataform + '&formid=' + formid;
			dsC.ajax({
				type: "POST",
				url: "/ds-comf/ds-form/form.php",
				dataType:  "json",  
				cache: false,
				data: dataform,
				success: formpost
			});

		} else {
			var formData = new FormData(dsC(this).get(0));
			formData.append('formid', formid);
			dsC.ajax({
		        url: "/ds-comf/ds-form/form.php",
		        type: "POST",
		        contentType: false,
		        processData: false,
		        data: formData,
		        dataType: 'json', 
		     	success: formpost
			});
		}

			return false;
		});

		function formpost(data) {
					formid = data['formid'];
					dsC('#' + formid + ' img.loadform').hide();
					dsC('#' + formid + ' input[type="submit"]').show();
					delete(data['formid']);
					if(data['error'] == 1) {
						 delete(data['error']);
						 dsC('#'+formid + ' .error_form').empty();
						 var error_array = [];
						 dsC.each(data, function(index, val) {
						 	if(dsC.inArray(val, error_array) == -1 && val!=1) error_array.push(val);
						 	dsC('#' + formid + ' *[name="'+ index +'"]').addClass('alert');
						 });
						 dsC('#' + formid + ' *[required]').each(function(){
						  	var field = dsC(this);
						  	if(field.hasClass('alert') && !data[field.attr('name')]) {
						  		field.removeClass('alert');
						  	}
						 });
						 var error_text = '<ul class="error-form">'+"\n";
						 dsC.each(error_array, function(index, val){
						 	error_text+='<li>'+val+'</li>'+"\n"; 
						 })
						 error_text+= '</ul>'+"\n";
						dsC('#'+formid + ' .error_form').append(error_text);
						dsC('#'+formid).css('height','auto');

					} else if(data['error'] == 0 || data['error'] == 2) {
						dsC('#' + formid + ' form').remove();
						dsC('#'+formid + ' .scrollform').css('height','auto');
						dsC('#'+formid).css('height','auto');
						dsC('#' + formid).append(data['error_text']);
					}
				}


		dsC('body').on('keyup','form input,form textarea', function(){
			var field = dsC(this);
			if(field.attr('pattern') && !field.val().match(field.attr('pattern'))) {
				field.addClass('alert');
			} else if(field.attr('pattern') && field.hasClass('alert')) {
				field.removeClass('alert');
			}
			if(!field.attr('pattern') && field.hasClass('alert')) {
				field.removeClass('alert');
			}
		});
		dsC('body').on('focusin', 'form input, form textarea, form select', function(){
				var formid = '#' + dsC(this).parents('form').attr("id");
				dsC('form input[type="text"], form textarea, form select').each(function(){
					dsC(this).removeClass('focusout');
				});
		});
		dsC('body').on('focusout','form input[type="text"],form textarea, form select', function(){
			dsC(this).addClass('focusout');
		});
		dsC('*[data-dspopup-id]').each(function () {
			var modalLocation = dsC(this).attr('data-dspopup-id');
			dsC('#' + modalLocation).addClass('dspopup-modal');
			if(dsconfig = dsC(this).attr('data-dsconfig')) {
				dsC('#' + modalLocation).attr('data-dsconfig',dsconfig);
				console.log('bingo');
			} else console.log('none');
		});

		dsC('.ds-form').each(function () {
				var formid = dsC(this).attr("id");
				addForm(formid);
		});

		dsC('body').on('click', '.repeatform', function(){
			var formid = dsC(this).parents('div.ds-form').attr('id');
			dsC('.error-report').remove();
			addForm(formid);
		});

		function addForm(formid) {
				var datajax;
				datajax = "formid=" + formid;
				if(dsconfig = dsC('#' + formid).attr('data-dsconfig')) {
					dsconfig = dsconfig.replace(/'/g,'"');
					if(!JSON.parse(dsconfig)) console.error('JSON array not valid!');
					datajax += "&dsconfig=" + dsconfig;
				}
				dsC.ajax({
				url: "/ds-comf/ds-form/formtpl.php",
			    type: "POST",
				dataType: "text",
				data:  datajax, 
				cache: false,
				success: function(data) {
					if(data!="error") {
						dsC('#'+formid).append(data);
						 if(!dsC('#'+formid).hasClass('dspopup-modal')) {
							dsC('#' + formid + ' *[autofocus]').focus();
						 }
						if (!window.FormData) {
						   dsC('#' + formid + ' *[type="file"]').css('display','none');
						}
						if(buttonheight = dsC('#' + formid + ' input[type="submit"]').outerHeight()) {
							var buttonwidth = dsC('#' + formid + ' input[type="submit"]').outerWidth();
							dsC('#' + formid + ' input[type="submit"]').before('<img src="/ds-comf/ds-form/images/loading.gif" class="loadform">');
							dsC('#' + formid + ' img.loadform').height(buttonheight);
							dsC('#' + formid + ' img.loadform').css('margin-left', (Number(buttonwidth)-Number(buttonheight))/2);
							dsC('#' + formid + ' img.loadform').css('margin-right', (Number(buttonwidth)-Number(buttonheight))/2);
							dsC('#' + formid + ' img.loadform, #' + formid + ' input[type="submit"]').css('vertical-align','top')
						}

					}
				}
				});
		}

		/*---------------------------
		 Defaults for dsReveal
		----------------------------*/

		/*---------------------------
		 Listener for data-dspopup-id attributes
		----------------------------*/
		dsC('*[data-dspopup-id]').on('click', function(e) {
			e.preventDefault();
			var modalLocation = dsC(this).attr('data-dspopup-id');
			var withForm = dsC('#'+modalLocation).innerWidth();
			var heightForm = dsC('#'+modalLocation).innerHeight();
			var windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
			if ( windowHeight  <= (heightForm + 20)) {
				var topForm=20;
				var marginTopForm=0;
				var overflowDiv = 'scroll';
				var contentForm = dsC('#'+modalLocation).html();
				var paddingForm = heightForm - dsC('#'+modalLocation).height();
				dsC('#'+modalLocation).width(dsC('#'+modalLocation).width()+20);
				dsC('#'+modalLocation).html('<div class="scrollform">' + "\n" + contentForm + '</div>');
				heightForm = windowHeight - 40 - paddingForm;
				dsC('.scrollform').css('height', heightForm - paddingForm);
				dsC('.scrollform').css('overflow-y' , 'scroll');
				dsC('#'+modalLocation).css('overflow' , 'hidden');
			} else {
				var topForm=Math.round(windowHeight/2);
				var marginTopForm=-Math.round(heightForm/2);
				var overflowDiv = 'visible';
				heightForm = dsC('#'+modalLocation).height();
			}

			dsC('#'+modalLocation).append('<div class="close-dspopup-modal"></div>');
			setTimeout(function(){
				dsC('#'+ modalLocation + ' *[autofocus]').focus();
			}, 1000);
			
			dsC('#'+modalLocation).dspopup(dsC(this).data());

			dsC('#'+modalLocation).css({
				'top':topForm + 'px',
				'left':'50% !important',
				'margin-left': -Math.round(withForm/2) + 'px',
				'margin-top': marginTopForm + 'px',
				'height' : heightForm
			});

		});

		/*---------------------------
		 Extend and Execute
		----------------------------*/

	    dsC.fn.dspopup = function(options) {
	        
	        
	        var defaults = {  
		    	animation: 'fade', //fade, fadeAndPop, none
			    animationspeed: 300, //how fast animtions are
			    closeonbackgroundclick: true, //if you click background will modal close?
			    dismissmodalclass: 'close-dspopup-modal' //the class of a button or element that will close an open modal
	    	}; 
	    	
	        //Extend dem' options
	        var options = dsC.extend({}, defaults, options); 
		
	        return this.each(function() {
		        
		/*---------------------------
		 Global Variables
		----------------------------*/
	        	var modal = dsC(this),
	        		topMeasure  = parseInt(modal.css('top')),
					topOffset = modal.height() + topMeasure,
	          		locked = false,
					modalBG = dsC('.dspopup-modal-bg');

		/*---------------------------
		 Create Modal BG
		----------------------------*/
				if(modalBG.length == 0) {
					modalBG = dsC('<div class="dspopup-modal-bg" />').insertAfter(modal);
				}		    
		     
		/*---------------------------
		 Open & Close Animations
		----------------------------*/
				//Entrance Animations
				modal.bind('dspopup:open', function () {
				  modalBG.unbind('click.modalEvent');
					dsC('.' + options.dismissmodalclass).unbind('click.modalEvent');
					if(!locked) {
						lockModal();
						if(options.animation == "fadeAndPop") {
							modal.css({'top': dsC(document).scrollTop()-topOffset, 'opacity' : 0, 'visibility' : 'visible'});
							modalBG.fadeIn(options.animationspeed/2);
							modal.delay(options.animationspeed/2).animate({
								"top": dsC(document).scrollTop()+topMeasure + 'px',
								"opacity" : 1
							}, options.animationspeed,unlockModal());					
						}
						if(options.animation == "fade") {
							modal.css({'opacity' : 0, 'visibility' : 'visible', 'top': dsC(document).scrollTop()+topMeasure});
							modalBG.fadeIn(options.animationspeed/2);
							modal.delay(options.animationspeed/2).animate({
								"opacity" : 1
							}, options.animationspeed,unlockModal());					
						} 
						if(options.animation == "none") {
							modal.css({'visibility' : 'visible', 'top':dsC(document).scrollTop()+topMeasure});
							modalBG.css({"display":"block"});	
							unlockModal()				
						}
					}
					modal.unbind('dspopup:open');
				}); 	

				//Closing Animation
				modal.bind('dspopup:close', function () {
				  if(!locked) {
						lockModal();
						if(options.animation == "fadeAndPop") {
							modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
							modal.animate({
								"top":  dsC(document).scrollTop()-topOffset + 'px',
								"opacity" : 0
							}, options.animationspeed/2, function() {
								modal.css({'top':topMeasure, 'opacity' : 1, 'visibility' : 'hidden'});
								unlockModal();
							});					
						}  	
						if(options.animation == "fade") {
							modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
							modal.animate({
								"opacity" : 0
							}, options.animationspeed, function() {
								modal.css({'opacity' : 1, 'visibility' : 'hidden', 'top' : topMeasure});
								unlockModal();
							});					
						}  	
						if(options.animation == "none") {
							modal.css({'visibility' : 'hidden', 'top' : topMeasure});
							modalBG.css({'display' : 'none'});	
						}		
					}
					modal.unbind('dspopup:close');
				});     
		   	
		/*---------------------------
		 Open and add Closing Listeners
		----------------------------*/
		        	//Open Modal Immediately
		    	modal.trigger('dspopup:open')
					
				//Close Modal Listeners
				var closeButton = dsC('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
				  modal.trigger('dspopup:close')
				});
				
				if(options.closeonbackgroundclick) {
					modalBG.css({"cursor":"pointer"})
					modalBG.bind('click.modalEvent', function () {
					  modal.trigger('dspopup:close')
					});
				}
				dsC('body').keyup(function(e) {
	        		if(e.which===27){ modal.trigger('dspopup:close'); } // 27 is the keycode for the Escape key
				});
				
					
		/*---------------------------
		 Animations Locks
		----------------------------*/
				function unlockModal() { 
					locked = false;
				}
				function lockModal() {
					locked = true;
				}	
					
	        });//each call
	    }//orbit plugin call
	});
}