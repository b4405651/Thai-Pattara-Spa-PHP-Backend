<?php
/*------------------------------------------------------------------------
# com_zhyandexmap - Zh YandexMap
# ------------------------------------------------------------------------
# author:    Dmitry Zhuk
# copyright: Copyright (C) 2011 zhuk.cc. All Rights Reserved.
# license:   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
# website:   http://zhuk.cc
# Technical Support Forum: http://forum.zhuk.cc/
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

	$user		= JFactory::getUser();
	$userId = $user->id;

	foreach($this->items as $i => $item): 
	
	$canDo = ZhYandexMapHelper::getPathActions($item->id);
	
	$canEdit    = $canDo->get('core.edit');
	$canEditOwn = $canDo->get('core.edit.own') && 1==2; //&& $item->createdbyuser == $userId;
	$canChange  = $canDo->get('core.edit.state');
	
?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<?php if ($canEdit || $canEditOwn) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_zhyandexmap&task=mappath.edit&id=' . $item->id); ?>">
					<?php echo $this->escape($item->title); ?></a>
			<?php else : ?>
					<?php echo $this->escape($item->title); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $this->escape($item->mapname); ?>
		</td>
		<td align="center">
			<?php 
				echo JHtml::_('jgrid.published', $item->published, $i, 'mappaths.', $canChange, 'cb', $item->publish_up, $item->publish_down); 
				//echo '<img src="'.JURI::root() .'administrator/components/com_zhyandexmap/assets/utils/published'.$item->published.'.png" alt="" />'; 
			?>			
		</td>
		<td>
			<?php echo $this->escape($item->category); ?>
		</td>
	</tr>
<?php endforeach; ?>

