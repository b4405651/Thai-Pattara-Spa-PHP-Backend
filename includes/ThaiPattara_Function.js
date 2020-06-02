function format_cart(response){
	var cart_str = response.count + ' ';
	if(lang == "ru" || lang == '') {
		switch (response.count) {
			case 0: case 1:
				cart_str += 'программа';
				break;
			case 2: case 3: case 4:
				cart_str += 'программы';
				break;
			default:
				cart_str += 'программ';
				break;
		}
	} else cart_str += 'item' + (response.count > 1 ? 's' : '');
			
	if(response.count == 0) jQuery('#view_cart_btn').hide();
	else {
		cart_str += '<font class="muted"> - </font><font class="text-warning"';
		if(lang == 'ru') cart_str += ' style="font-size: 14px;"';
		cart_str += '>' + response.total + '</font><font class="muted">';
		if(lang == 'en' || lang == '') cart_str += ' rub.';
		else if(lang == 'ru') cart_str += ' руб.';
		cart_str += '</font>';
		jQuery('#view_cart_btn').show();
	}
	
	jQuery('#shopping_cart').html(cart_str);
}

function format_cart_detail(response){
	if(response.count == 0) jQuery('#cart_view').dialog('close');
	else {
		jQuery('#cart_view').find('table').find('tr').remove();
		jQuery.each(response.RESULT, function(i, el){
			var row_str = '<tr style="font-weight: bold;"' + (i % 2 != 0 ? ' class="success"':'') + '>';
			row_str += "<td>" + (i + 1) + "</td>";
			row_str += "<td>" + el.NAME + "</td>";
			row_str += "<td style='text-align: right'>" + formatNumber(el.PRICE) + " " + (lang == 'en' || lang == '' ? 'rub.' : '') + (lang == 'ru' ? 'руб.' : '') + "</td>";
			row_str += '<td><a class="btn btn-mini btn-danger" onclick="cart_pop(' + el.id + '); return false;" title="' + (lang == 'ru' ? 'Удалить объект' : 'remove this item') + '"><i class="icon-remove icon-white"></i></a></td>';
			row_str += "</tr>";
			jQuery('#cart_view').find('table').append(row_str);
		});
	}
}

function cart_view(){
	jQuery.post(rootPath + "cart.php", {}, function(response){
		if(response.type == 'success'){
			format_cart(response);
		}
	}, 'json');
}

function display_cart(){
	jQuery.post(rootPath + "cart.php", {}, function(response){
		if(response.type == 'success'){
			format_cart_detail(response);
			jQuery("#cart_view").dialog('open');
		}
	}, 'json');
}

function cart_push(item, name, price, time){
	var item_price = "";
	if(price == null) item_price = jQuery(item).closest('td').prev('td').find('.price').html().replace(',','');
	else item_price = jQuery(price).html().replace(',',''); 
	
	var item_time = "";
	if(time == null) item_time = jQuery(item).closest('td').prev('td').prev('td').html();
	else item_time = jQuery(time).html(); 
	
	var item_name = "";	
	if(name == null) {
		item = jQuery(item).closest('tr');
		if(jQuery(item).closest('tr').find('td').length != 4) {
			while(jQuery(item).find('td').length < 4){
				item = jQuery(item).prev('tr');
			}
		}
		item_name = jQuery(item).find('td:first').find('.item_name').html();
	}
	else item_name = jQuery(name).html();
	item_name += ' ' + item_time;
	
	jQuery.post(rootPath + "cart_push.php", {"NAME": item_name, "PRICE": item_price}, function(response){
		if(response.type == 'success'){
			format_cart(response);
		}
	}, 'json');
}

function cart_pop(index){
	jQuery.post(rootPath + "cart_pop.php", {"REMOVE_ID": index}, function(response){
		if(response.type == 'success'){
			format_cart_detail(response);
			format_cart(response);
		}
	}, 'json');
}

function cart_clear(){
	jQuery.post(rootPath + "cart_clear.php", {}, function(response){
		if(response.type == 'success'){
			format_cart(response);
			jQuery('#cart_view').dialog('close');
		}
	}, 'json');
}

function cart_checkout(){
	document.getElementById('bookingFrm').reset();
	grecaptcha.reset(captchaWidget);
    jQuery("#booking_datetime").datetimepicker({
		lang:lang,
		format:dateFormat + ' H:i',
		step: 15
	});

	jQuery.post(rootPath + "cart.php", {}, function(response){
		if(response.type == 'success'){
			var txt = "";
			jQuery.each(response.RESULT, function(i, el){
				txt += (i+1) + ". " + el.NAME + " (" + formatNumber(el.PRICE);
				if(lang == 'ru' || lang == '') txt += " руб.";
				if(lang == 'en') txt += " rub.";
				txt += ")<br />";
			});
			jQuery('#spa_program').html(txt);
			var response_txt = "";
			if(lang == 'ru' || lang == '') response_txt = response.total + ' руб.';
			if(lang == 'en') response_txt = response.total + ' Rub.';
			jQuery('#total_price').html(response_txt);
			jQuery("#cart_view").dialog('close');
			jQuery('.booking_form').trigger('click');
		}
	}, 'json');
}

function giftcer_checkout(){
	if(jQuery('#spa_program_tr').is(':visible')) {
		if(jQuery('#SPA_PROGRAM').val() == ''){
			if(lang == 'ru' || lang == '') alert('Выберете спа программу');
			else alert('YOU FORGOT TO CHOOSE THE SPA PROGRAM ?');
			jQuery('#SPA_PROGRAM').focus();
			return false;
		}
	}
	
	if(jQuery('#money_tr').is(':visible')) {
		if(jQuery('#money_amount').val().trim() == ''){
			if(lang == 'ru' || lang == '') alert('ошибка');
			else alert('YOU FORGOT TO FILL THE MONEY AMOUNT OF THE CARD ?');
			jQuery('#money_amount').focus();
			return false;
		}
	}
	
	if(jQuery('#sender_name').val().trim() == '' || jQuery('#sender_email').val().trim() == '' || jQuery('#sender_phone').val().trim() == '' || jQuery('#recipient_name').val().trim() == '' || jQuery('#recipient_email').val().trim() == '' || jQuery('#subject').val().trim() == '' || jQuery('#message').val().trim() == '')
	{
		if(lang == 'ru' || lang == '') alert('ошибка');
		else alert('YOU FORGOT TO FILL SOMETHING ?');
		return false;
	}	
	
	jQuery('#giftcer_form').attr('action', rootPath + 'THAIPATTARA_PAYPAL.php');
	
	var paramStr = "&return_param=";
	paramStr += 'card#giftcer';
	paramStr += '!lang#' + lang;
	paramStr += '!card_type#' + jQuery('#card_type').val();
	paramStr += '!spa_program#' + jQuery('#SPA_PROGRAM option:selected').text().split(' -- ')[0];
	paramStr += '!money_amount#' + jQuery('#money_amount').val();
	paramStr += '!original_price#' + current_price;
	paramStr += '!price#' + jQuery('#price').html().replace(',', '');
	paramStr += '!currency#Rub';
	paramStr += '!currency_text#Ruble';
	paramStr += '!sender_name#' + jQuery('#sender_name').val();
	paramStr += '!sender_email#' + jQuery('#sender_email').val();
	paramStr += '!sender_phone#' + jQuery('#sender_phone').val();
	paramStr += '!recipient_name#' + jQuery('#recipient_name').val();
	paramStr += '!recipient_email#' + jQuery('#recipient_email').val();
	paramStr += '!subject#' + jQuery('#subject').val();
	paramStr += '!message#' + jQuery('#message').val().replace(/\n/g, "@@@");
	
	var itemStr = "Electronic Gift Certificate for ";
	if(jQuery('#spa_program_tr').is(':visible')) itemStr += jQuery('#SPA_PROGRAM option:selected').text().split(' -- ')[0];
	if(jQuery('#money_tr').is(':visible')) itemStr += 'Money ' + formatNumber(jQuery('#money_amount').val()) + ' Rub.';
	itemStr +=  '!' + jQuery('#price').html().replace(',','') + '.00';
	
	jQuery('input[name=itemStr]').val(itemStr);	
	jQuery('input[name=return_param]').val(paramStr);
	
	jQuery.each(jQuery('input[type=hidden]'), function(i, el){
		console.log(i + '. ' + jQuery(el).attr('name') + ' = ' + jQuery(el).val());
	});
	
	jQuery('#giftcer_form').submit();
}

function membercard_checkout(){
	if(jQuery('#customer_name').val() == ''){
		if(lang == 'ru' || lang == '') alert('Пожалуйста, введите ваше полное имя');
		else alert('YOU FORGOT TO ENTER YOUR NAME ?');
		jQuery('#customer_name').focus();
		return false;
	}
	
	if(jQuery('#customer_email').val().trim() == ''){
		if(lang == 'ru' || lang == '') alert('Пожалуйста, введите ваш E-MAIL');
		else alert('YOU FORGOT TO ENTER YOUR E-MAIL ?');
		jQuery('#customer_email').focus();
		return false;
	}
	
	if(jQuery('#customer_phone').val().trim() == ''){
		if(lang == 'ru' || lang == '') alert('Пожалуйста, введите ваш номер телефона');
		else alert('YOU FORGOT TO ENTER YOUR PHONE NUMBER ?');
		jQuery('#customer_phone').focus();
		return false;
	}
	
	jQuery('#member_card_checkout_form').attr('action', rootPath + 'THAIPATTARA_PAYPAL.php');
	
	var paramStr = "&return_param=";
	paramStr += 'card#membercard';
	paramStr += '!lang#' + lang;
	paramStr += '!card_type#' + jQuery('#member_card_type').html();
	paramStr += '!original_price#' + current_price;
	paramStr += '!price#' + jQuery('#price').html().replace(',', '');
	paramStr += '!currency#Rub';
	paramStr += '!currency_text#Ruble';
	paramStr += '!customer_name#' + jQuery('#customer_name').val();
	paramStr += '!customer_email#' + jQuery('#customer_email').val();
	paramStr += '!customer_phone#' + jQuery('#customer_phone').val();
	
	var itemStr = jQuery('#member_card_type').html();
	itemStr +=  '!' + jQuery('#price').html().replace(',', '') + '.00';
	
	jQuery('input[name=itemStr]').val(itemStr);	
	jQuery('input[name=return_param]').val(paramStr);
	
	jQuery.each(jQuery('input[type=hidden]'), function(i, el){
		console.log(i + '. ' + jQuery(el).attr('name') + ' = ' + jQuery(el).val());
	});
	
	jQuery('#member_card_checkout_form').submit();
}

function isValidCaptcha(){
	if(jQuery("#g-recaptcha-response").val() == ""){
		alert(invalidErrorMsg);
		return false;
	}
	return true;
}

function submitContactForm(){
	if(isValidCaptcha()){
		var post_data = {
			'user_name'     : jQuery('input[name="user_name"]').val(), 
			'user_email'    : jQuery('input[name="user_email"]').val(), 
			'phone_number'  : jQuery('input[name="phone_number"]').val(), 
			'subject'       : jQuery('input[name="subject"]').val(), 
			'msg'           : jQuery('textarea[name="msg"]').val(),
			'mail_subject'	: "CONTACT FORM"
		};
		post(post_data, rootPath + "sendEmail.php");
	}
}

function submitBookingForm(){
	var targetObj = null;
	if(jQuery('#fullname').val().trim().length == 0 && targetObj == null) targetObj = jQuery('#fullname');
	if(jQuery('#phone_number').val().trim().length == 0 && targetObj == null) targetObj = jQuery('#phone_number');
	if(jQuery('#email').val().trim().length == 0 && targetObj == null) targetObj = jQuery('#email');
	if(jQuery('#booking_datetime').val().trim().length == 0 && targetObj == null) targetObj = jQuery('#booking_datetime');
	
	if(targetObj != null) {
		if(lang == 'ru' || lang == '') alert('ошибка');
		else alert('You forgot something ?');
		jQuery(targetObj).focus();
		return;
	}
	
	if(!isValidCaptcha()){
		var post_data = {
			'spa_program'	: jQuery('#spa_program').html().replace(/<br>/g, "\r\n").replace('&amp;', "&"),
			'total_price'	: jQuery('#total_price').html(),
			'fullname'     : jQuery('#fullname').val().trim(), 
			'phone_number'    : jQuery('#phone_number').val().trim(), 
			'email'  : jQuery('#email').val().trim(), 
			'datetime'       : jQuery('#booking_datetime').val().trim(), 
			'master'		: jQuery('#master').val().trim(),
			'note'           : jQuery('#note').val().trim(),
			'mail_subject'	: "NEW MASSAGE BOOKING FROM WEBSITE"
		};
		/*jQuery.each(post_data, function(i, el){
			console.log(i + ' = ' + el);
		});
		return false;*/
		post(post_data, rootPath + "submitBookingForm.php");
	}
}

function post(post_data, url){
	if(!post_data.blank_page) jQuery(".loading_dialog").dialog("open");
		
	jQuery.post(url, post_data, function(response){
		if(response.type == 'error'){
			if(!post_data.blank_page){
				jQuery(".loading_dialog").html("<span class='text-green'>" + response.text + " !!</span>");
				setTimeout( "jQuery('.loading_dialog').dialog('close');" + (response.targetObj != '' ? " jQuery('" + response.targetObj + "').focus();" : ""), 2000);
			} else {
				alert(response.text);
			}
			return;
		} else if(response.type == 'success') {
			if(!post_data.blank_page) jQuery(".loading_dialog").html("<span class='text-green'>" +doneMsg + " !!</span>");
			else alert(doneMsg);
			
			if(!post_data.blank_page) {
				switch(url){
					case rootPath + "sendEmail.php":
						setTimeout( "jQuery('.loading_dialog').dialog('close'); jQuery('input[type=\"reset\"]').click();", 2000);
						break;
					case rootPath + "submitBookingForm.php":
						setTimeout( "cart_clear(); jQuery('.loading_dialog').dialog('close'); window.close();", 2000);
						break;
				}
			} else window.close();
			return true;
		}
	}, 'json');
}

function formatNumber(number){
	return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}