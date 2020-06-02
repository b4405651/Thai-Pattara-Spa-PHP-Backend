<?php
// Protection contre les appels directs.
defined("_JEXEC") or die("Restricted access");
function modChrome_joomspirit($module, &$params, &$attribs) {
	$app = JFactory::getApplication();
	$isMobile = $app->getUserState('cmobile.ismobile', false);

   	// init vars
	$showtitle = $module->showtitle;
	$content   = $module->content;
	$suffix    = '';
	$badge		='';
	// create title
	$pos   = JString::strpos($module->title, ' ');
	$title = ($pos !== false) ? JString::substr($module->title, 0, $pos).'<span>'.JString::substr($module->title, $pos).'</span>' : $module->title;
 
	// force module type
	if ($module->position == 'logo')  $suffix = 'logo';
	if ($module->position == 'supersized')  $suffix = 'only_content';
	if ($module->position == 'google_map')  $suffix = 'only_content';
	if ($module->position == 'video')  $suffix = 'only_content';
	if ($module->position == 'menu')  $suffix = 'menu';
	if ($module->position == 'left')  $suffix = 'normal';
	if ($module->position == 'right')  $suffix = 'normal';
	if ($module->position == 'search')  $suffix = 'normal';
	if ($module->position == 'address')  $suffix = 'only_content';
	if ($module->position == 'top')  $suffix = 'normal';
	if ($module->position == 'bottom')  $suffix = 'normal';
	if ($module->position == 'top_menu')  $suffix = 'normal';
	if ($module->position == 'bottom_menu')  $suffix = 'normal';
	if ($module->position == 'translate')  $suffix = 'normal';
	if ($module->position == 'user1')  $suffix = 'normal';
	if ($module->position == 'user2')  $suffix = 'normal';
	if ($module->position == 'user3')  $suffix = 'normal';
	if ($module->position == 'user4')  $suffix = 'normal';
	if ($module->position == 'user5')  $suffix = 'normal';
	if ($module->position == 'user6')  $suffix = 'normal';
	
	// set module skeleton using the suffix
	switch ($suffix) {
		case 'logo':
			$skeleton = 'logo';
			break;
		case 'only_content':
			$skeleton = 'only_content';
			break;	
		case 'normal':
			$skeleton = 'normal';
			break;
		case 'menu':
			$skeleton = 'menu';
			break;		
		case 'no-title':
			$skeleton = 'no-title';
			break;			
		default:
			$skeleton = 'not defined';
	}
	// Modules
	switch ($skeleton) {
		case 'logo':
			/*
			 * logo module
			 */
			?>
			
				<div class=" <?php echo $params->get('moduleclass_sfx'); ?>" >
				<?php echo $content; ?>
				</div>
			
			
			<?php 
			break;
		case 'only_content':
			/*
			 * only_content module
			 */
			?>
				<?php echo $content; ?>
							
			<?php 
			break;
			
		case 'menu':
			/*
			 * no title modules
			 */
			?>
					<span class="title_menu" style="text-align: center">
						<a href="#navigation"><img src="images/0.png" alt="" />&nbsp;menu</a>
					</span>
					
					<?php echo $content; ?>

			<?php 
			break;
			
		case 'normal':
			/*
			 * normal
			 */
			?>
			<div class="moduletable <?php echo $params->get('moduleclass_sfx'); ?>" >
				<div>
					<?php if ($showtitle) : ?>
					<h3 class="module"><?php echo $title; ?></h3>
					<?php endif; ?>
			
					<div class="content-module">
						<?php echo $content; ?>
					</div>
				</div>
			</div>
			<?php 
			break;
			
		case 'no-title':
			/*
			 * no title modules
			 */
			?>
			<div class="moduletable <?php echo $params->get('moduleclass_sfx'); ?>" >
			
				<div class="content-module">
					<?php echo $content; ?>
				</div>

			</div>
			<?php 
			break;			
	
		default:
			/*
			 * not defined
			 */
			?>
			<div class="module <?php echo $suffix; ?>">
				<?php if ($showtitle) : ?>
				<h3 class="module"><?php echo $title; ?></h3>
				<?php endif; ?>
				<?php echo $content; ?>
			</div>
			<?php 
			break;
	}
}
?>