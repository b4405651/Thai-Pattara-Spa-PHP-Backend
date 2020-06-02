<?php
/**
 * @package    Ccomment
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       12.01.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

// Output the js localisation
ccommentHelperUtils::getJsLocalization();
$document = JFactory::getDocument();
$component = $this->component;
$count = $this->count;
$id = $this->contentId;
$user = JFactory::getUser();
$modules = JModuleHelper::getModules('ccomment-top');

$htmlId = 'ccomment-' . str_replace('com_', '', $component) . '-' . $id;
$config = $this->config;
$avatar = '';

if ($config->get('template_params.form_avatar'))
{
	if ($user->guest)
	{
		$avatar = ccommentHelperAvatars::noAvatar();
	}
	else
	{
		$avatar = ccommentHelperAvatars::getUserAvatar($user->id, $config->get('integrations.support_avatars'));

		if ($avatar == '' && $config->get('integrations.gravatar'))
		{
			$avatar = ccommentHelperAvatars::getUserGravatar($user->email);
		}

		// If we still don't have an avatar here, let us load the no avatar image
		if ($avatar == '')
		{
			$avatar = ccommentHelperAvatars::noAvatar();
		}
	}
}

$userInfo = array(
	'loggedin' => !$user->guest,
	'avatar' => $avatar
);

$pageItem = array('contentid' => (int) $id, 'component' => $component, 'count' => (int) $count);

JHtml::_('behavior.framework', true);

// Keep the session alive - the user is writing comments!!!
JHtml::_('behavior.keepalive');


JHtml::_('behavior.formvalidation');

if ($config->get('template_params.emulate_bootstrap', 1))
{
	JHtml::stylesheet('media/com_comment/templates/default/css/bootstrap.css');
}

JHtml::stylesheet('media/com_comment/templates/default/css/default.css');

// Ontouchstart -> makes sure that the device we are currently on support/doesn't support touch events
$initCode = "

window.compojoom = compojoom = window.compojoom || {};
	compojoom.ccomment = {
			user: " . json_encode($userInfo) . ",
					item: " . json_encode($pageItem) . ",
					config: " . json_encode(CcommentHelperUtils::getJSConfig($component)) . "
				};

window.addEvent('domready', function() {
	if (!('ontouchstart' in document.documentElement)) {
	    document.documentElement.className += ' ccomment-no-touch';
	}

	var commentsCollection = new Ccomment.Comments([]),
		hash = location.hash, startPage = 1, comment = 0, params = {};

	var cv = new Ccomment.CommentsOuter({
		collection: commentsCollection,
		template: document.id('comment-outer-template').get('html'),
		element: '" . $htmlId . "'
	});

	if(hash.indexOf('#!/ccomment-page') === 0) {
		startPage = hash.replace('#!/ccomment-page=','');
		params = {start: startPage};
	}

	if(hash.indexOf('#!/ccomment-comment=') === 0) {
		comment = hash.replace('#!/ccomment-comment=','');
		params = {comment: comment};
	}

	commentsCollection.fetch(false, params);
});";

// If we have a guest user and if the cache is on, we need to create a JS file, otherwise
// the js will be added twice or more times to the head to the head
if ($user->guest && JFactory::getConfig()->get('caching', 0))
{
	JHtml::script(
		CcommentHelperJs::createFile(
			json_encode($userInfo) . (int) $id . $component . JSession::getFormToken(),
			$initCode
		)
	);
}
else
{
	$document->addScriptDeclaration($initCode);
}

// Add the recaptcha js if we need it
if ($this->config->get('security.captcha') && $this->config->get('security.captcha_type') == 'recaptcha')
{
	CompojoomHtml::recaptcha();
}
?>
<div id="<?php echo $htmlId; ?>" class="ccomment row-fluid"></div>

<script  data-inline type="text/template" id="ccomment-comment-template">
	<?php echo $this->loadTemplate('comment'); ?>
</script>

<script  data-inline type="text/template" id="comment-outer-template">
	<?php if(count($modules)) : ?>
		<div class="ccomment-modules-top">
			<?php
				foreach ($modules as $module) {
					echo JModuleHelper::renderModule($module);
				}
			?>
		</div>
	<?php endif; ?>
	<ul class='ccomment-comments-list'>
		<?php if ($count) : ?>
			<li class="text-center"><?php echo JText::_('COM_COMMENT_LOADING_COMMENTS'); ?><span
					class="ccomment-loading"></span></li>
		<?php else : ?>
			<li class="ccomment-no-comments"><?php echo JText::_('COM_COMMENT_NO_COMMENTS_YET'); ?></li>
		<?php endif; ?>
	</ul>
</script>

<script data-inline type="text/template" id="ccomment-form-template">
	<?php echo $this->loadTemplate('form'); ?>
</script>

<script  data-inline type="text/template" id="ccomment-menu-template">
	<?php echo $this->loadTemplate('menu'); ?>
</script>

<?php if ($this->config->get('layout.show_copyright', 1)) : ?>
	<script data-inline type="text/template" id="ccomment-footer-template">
		<?php echo $this->loadTemplate('footer'); ?>
	</script>
<?php endif; ?>

<div id="ccomment-token" style="display:none;">
	<?php echo JHtml::_('form.token'); ?>
</div>

<?php

CompojoomHtml::script(
	array(
		'media/com_comment/js/dynamictextarea.js',
		'media/com_comment/js/libraries/placeholder/placeholder.js',
		'media/com_comment/js/String.MD5.js',
		'media/com_comment/js/String.UTF-8.js',
		'media/com_comment/js/libraries/mustache/mustache.js',
		'media/com_comment/js/libraries/epitome/epitome.js',
		'media/com_comment/js/libraries/epitome/epitome-isequal.js',
		'media/com_comment/js/libraries/epitome/epitome-model.js',
		'media/com_comment/js/libraries/epitome/epitome-model-sync.js',
		'media/com_comment/js/libraries/epitome/epitome-collection.js',
		'media/com_comment/js/libraries/epitome/epitome-collection-sync.js',
		'media/com_comment/js/libraries/epitome/epitome-template.js',
		'media/com_comment/js/libraries/epitome/epitome-view.js',
		'media/com_comment/js/models/user.js',
		'media/com_comment/js/models/comment.js',
		'media/com_comment/js/collections/comments.js',
		'media/com_comment/js/views/pagination.js',
		'media/com_comment/js/views/ubbcode.js',
		'media/com_comment/js/views/comments-outer.js',
	),
	'media/com_comment/cache',
	$config->get('template_params.minify_scripts', true),
	true
);
