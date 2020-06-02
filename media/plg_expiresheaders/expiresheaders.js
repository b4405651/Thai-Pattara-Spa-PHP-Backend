function addit() {
	var options = document.id('jform_params_menulist').getSelected('option');
	options.each(function(el){
		var menuvalue = el.value;
		var expires = document.id('jform_params_ruleexpires').value;
		var elength = document.id('jform_params_ruleexpireslength').value;
		var einterval = document.id('jform_params_ruleexpiresinterval').value;
		var cachecontrol = document.id('jform_params_rulecachecontrol').value;
		var publicprivate = document.id('jform_params_rulepublicprivate').value;
		var nostore = document.id('jform_params_rulenostore').checked;
		var mustrevalidate = document.id('jform_params_rulemustrevalidate').checked;
		var pragma = document.id('jform_params_rulepragma').checked;
		var parent = el.getParent('optgroup').get('label');
		var dash = /^(- )*/;
		var optext = parent + '-' + el.innerHTML.replace(dash,'');
		var opvalue = menuvalue + ':' + expires + ':' + elength + ':' + einterval + ':' + cachecontrol + ':' + publicprivate + ':' + nostore + ':' + mustrevalidate + ':' + pragma + ':' + escape(optext);
		var rules = document.id('jform_params_headerrules').getChildren('option');
		var injected = false;
		rules.each(function(cel){
			var celmenuval = cel.value.split(':')[0];
			if (celmenuval == menuvalue) {
				cel.value = opvalue;
				injected = true;
			}
		});
		if (injected == false) {
			new Option(optext,opvalue).inject(document.id('jform_params_headerrules'));
		}
	});
	updaterules();
	return false;
};
function updaterules() {
	var rulesarray = new Array();
	var rules = document.id('jform_params_headerrules').getChildren('option');
	rules.each(function(cel){ 
		rulesarray.push(cel.value); 
		cel.removeEvents('click');
		cel.addEvent('click',function(){
			updateit(this);
		});
	});
	document.id('jform_params_headerrulestext').value = rulesarray.join('#');
}
function removeit() {
	var options = document.id('jform_params_headerrules').getSelected('option');
	options.each(function(el){
		el.destroy();  
	});
	updaterules();
	return false;
};
function updateit(el) {
	var values = el.value.split(':');
	var optgroups = document.id('jform_params_menulist').getChildren();
	var options = new Array();
	optgroups.each(function(optgroup){
		optgroup.getChildren().each(function(ogchild){
			options.push(ogchild);
		});
	});
	options.each(function(option){
		if (option.value == values[0]) {
			option.selected = true;
		} else {
			option.selected = false;
		}

	});
		document.id('jform_params_ruleexpires').getChildren('option').each(function(rel){
			if(rel.value == values[1]) {
				rel.selected=true;
			} else {
				rel.selected=false;
			}
		});
		document.id('jform_params_ruleexpireslength').value = values[2];
		document.id('jform_params_ruleexpiresinterval').value = values[3];
		document.id('jform_params_rulecachecontrol').getChildren('option').each(function(rel){
			if(rel.value == values[4]) {
				rel.selected=true;
			} else {
				rel.selected=false;
			}
		});
		document.id('jform_params_rulepublicprivate').getChildren('option').each(function(rel){
			if(rel.value == values[5]) {
				rel.selected=true;
			} else {
				rel.selected=false;
			}
		});
		document.id('jform_params_rulenostore').checked = (values[6] === 'true');
		document.id('jform_params_rulemustrevalidate').checked = (values[7] === 'true');
		document.id('jform_params_rulepragma').checked = (values[8] === 'true');
	return false;
};
function beforesubmit() {
	document.id('jform_params_ruleexpires').destroy();
	document.id('jform_params_ruleexpireslength').destroy();
	document.id('jform_params_ruleexpiresinterval').destroy();
	document.id('jform_params_rulecachecontrol').destroy();
	document.id('jform_params_rulepublicprivate').destroy();
	document.id('jform_params_rulenostore').destroy();
	document.id('jform_params_rulemustrevalidate').destroy();
	document.id('jform_params_rulepragma').destroy();
	document.id('jform_params_headerrules').destroy();
	document.id('jform_params_menulist').destroy();
};
window.addEvent('domready',function(){
	document.id('jform_params_ruleexpires').value = 1;
	document.id('jform_params_ruleexpireslength').value = 2;
	document.id('jform_params_ruleexpiresinterval').value = 'hour';
	document.id('jform_params_rulecachecontrol').value = 1;
	document.id('jform_params_rulepublicprivate').value = 'public';
	document.id('jform_params_rulenostore').checked = false; 
	document.id('jform_params_rulemustrevalidate').checked = false; 
	document.id('jform_params_rulepragma').checked = false; 
	$$('#toolbar-apply a')[0].setProperty('onclick','javascript:return false;');
	$$('#toolbar-save a')[0].setProperty('onclick','javascript:return false;');
	$$('#toolbar-apply a')[0].addEvent('click',function(){
		beforesubmit();
		submitbutton('plugin.apply');
	});
	$$('#toolbar-save a')[0].addEvent('click',function(){
		beforesubmit();
		submitbutton('plugin.save');
	});
	var rulesarray = document.id('jform_params_headerrulestext').value.split('#');
	for (i=0;i<rulesarray.length;i++) {
		var ruletext = rulesarray[i].split(':')[9];
		new Option(unescape(ruletext),rulesarray[i]).inject(document.id('jform_params_headerrules'));
	}
	updaterules();
	document.id('jform_params_headerrules').getChildren('option').each(function(el) {
		if(el.value.length == 0) el.destroy();
	});
});