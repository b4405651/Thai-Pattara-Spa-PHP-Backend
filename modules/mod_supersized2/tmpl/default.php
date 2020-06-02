<?php // no direct access
/**
* @version 2.0 for Joomla! 2.5
* @copyright (C) 2013 JoomSpirit
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @Author name JoomSpirit 
* @Author website  http://www.joomspirit.com/
*/

defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

jQuery.noConflict();
jQuery(function(jQuery){
jQuery.supersized({

slideshow : <?php echo $slideshow ; ?>,
autoplay : 1 ,
start_slide : 1 ,
stop_loop : <?php echo $stop_loop ; ?> ,
random : <?php echo $random ; ?> ,
slide_interval : <?php echo $slide_interval ; ?> ,
transition : <?php echo $transition ; ?> ,
transition_speed : <?php echo $transition_speed ; ?> ,
pause_hover : 0 ,
keyboard_nav : 0 ,
performance	: <?php echo $performance ; ?> ,
image_protect : <?php echo $image_protect ; ?> ,
show_thumb : <?php if ($random =='1' ) { echo('0'); } else { echo $show_thumb ; } ?> ,
image_path : '<?php echo JURI::base(); ?>templates/full_screen_2/images/supersized/',

fit_always : <?php echo $fit_always ; ?> ,
fit_portrait : <?php echo $fit_portrait ; ?> ,
fit_landscape : <?php echo $fit_landscape ; ?> ,
min_width : <?php echo $min_width ; ?> ,
min_height : <?php echo $min_height ; ?> ,
vertical_center : <?php echo $vertical_center ; ?> ,
horizontal_center : <?php echo $horizontal_center ; ?> ,
					
slides : [

<?php $comma = FALSE; ?>

<?php
	foreach($listofimages as $item){
	
		if($comma) { echo ','; }
		$comma = TRUE;
		echo $item; 
	
	
	}
?> 

	]
	});
});