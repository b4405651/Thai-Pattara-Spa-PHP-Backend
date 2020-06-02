<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       07.10.14
 *
 * @copyright  Copyright (C) 2008 - 2014 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.framework');
JHtml::_('behavior.tooltip');
$init = "window.addEvent('domready', function() {new settings({element: 'jform_template_template', component: '" . $this->item->component . "'})});";
$document = JFactory::getDocument();
$document->addScriptDeclaration($init);
$input = JFactory::getApplication()->input;

JToolbarHelper::apply('settings.apply');
JToolbarHelper::save('settings.save');
JToolbarHelper::cancel('settings.cancel');

JHtml::script('media/com_comment/backend/js/settings.js');
echo CompojoomHtmlCtemplate::getHead(CcommentHelperMenu::getMenu(), 'settings', '', '');
?>
	<div class="box-info full">

		<h2>Comment configuration for <?php echo $input->getCmd('component'); ?></h2>

		<div class="additional-btn">
			<label class="ccomment-note-label"><?php echo JText::_('COM_COMMENT_NOTE_LABEL'); ?>:</label>
			<input type="text" class="input-xlarge" name="note" placeholder="<?php echo JText::_('COM_COMMENT_NOTE_PLACEHOLDER'); ?>"
			       value="<?php echo $this->item->note; ?>" style="margin-bottom: 9px;"/>
		</div>
		<form action='index.php' method='POST' name='adminForm' id="adminForm" class="form-validate form-horizontal">


			<ul class="nav nav-tabs nav-justified">
				<li class="active">
					<a data-toggle="tab" href="#general">
						<?php echo JText::_('TAB_GENERAL_PAGE'); ?></a></li>
				<li><a data-toggle="tab" href="#security"><?php echo JText::_('TAB_SECURITY'); ?></a></li>
				<li><a data-toggle="tab" href="#layout"><?php echo JText::_('TAB_LAYOUT'); ?></a></li>
				<li><a data-toggle="tab" href="#template"><?php echo JText::_('COM_COMMENT_TAB_TEMPLATE'); ?></a></li>
				<li><a data-toggle="tab" href="#integrations"><?php echo JText::_('COM_COMMENT_TAB_INTEGRATIONS'); ?></a>
				</li>
			</ul>
			<div class="tab-content">
				<?php $tabs = array('general', 'security', 'layout', 'template', 'integrations'); ?>
				<?php foreach ($tabs as $key => $value) : ?>
					<div id="<?php echo $value; ?>" class="tab-pane <?php echo $key == 0 ? 'active' : ''; ?>">
						<?php if (!CCOMMENT_PRO) : ?>
							<span class="ccomment-pro">
							* <?php echo JText::sprintf('COM_COMMENT_PRO_NOTICE', 'https://compojoom.com/joomla-extensions/compojoomcomment'); ?>
						</span>
						<?php endif; ?>
						<?php require_once($value . '.php'); ?>
					</div>
				<?php endforeach; ?>
			</div>


			<input type="hidden" name="id" value="<?php echo $this->item->id > 0 ? $this->item->id : ''; ?>"/>
			<input type="hidden" name="component" value="<?php echo $this->item->component; ?>"/>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="option" value="com_comment"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
<?php
// Show Footer
echo CompojoomHtmlCTemplate::getFooter(CcommentHelperBasic::getFooterText());
