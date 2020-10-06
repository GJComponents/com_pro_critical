<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Gartes 
/-------------------------------------------------------------------------------------------------------/

	@version		1.x.x
	@build			23rd августа, 2020
	@created		5th мая, 2019
	@package		proCritical
	@subpackage		edit.php
	@author			Nikolaychuk Oleg <https://nobd.ml>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$componentParams = $this->params; // will be removed just use $this->params instead
?>
<div id="pro_critical_loader">
<form action="<?php echo JRoute::_('index.php?option=com_pro_critical&layout=edit&id='. (int) $this->item->id . $this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

	<?php echo JLayoutHelper::render('html_task.manager_html_task_seting_above', $this); ?>
<div class="form-horizontal">
	<div class="span9">

	<?php echo JHtml::_('bootstrap.startTabSet', 'html_taskTab', array('active' => 'manager_html_task_seting')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'html_taskTab', 'manager_html_task_seting', JText::_('COM_PRO_CRITICAL_HTML_TASK_MANAGER_HTML_TASK_SETING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('html_task.manager_html_task_seting_left', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'html_taskTab', 'params_query', JText::_('COM_PRO_CRITICAL_HTML_TASK_PARAMS_QUERY', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('html_task.params_query_left', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'html_taskTab', 'xparh_help', JText::_('COM_PRO_CRITICAL_HTML_TASK_XPARH_HELP', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
				 <tr>
				                        <th>XPath</th>
				                        <th>Описание</th>
				                    </tr>
				
				                    <tr>
				                        <td><code>//body//div[@id='XXXXXX']</code></td>
				                        <td>Вернет элемент div с id XXXXXX</td>
				                    </tr>
				                    <tr>
				                        <td><code>//body//div//li/a[contains(@class, 'XXXXXX')]</code></td>
				                        <td>Найдет в ссылки в списках с классом XXXXXX</td>
				                    </tr>
				
				</table>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php $this->ignore_fieldsets = array('details','metadata','vdmmetadata','accesscontrol'); ?>
	<?php $this->tab_name = 'html_taskTab'; ?>
	<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

	<?php if ($this->canDo->get('core.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('core.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'html_taskTab', 'publishing', JText::_('COM_PRO_CRITICAL_HTML_TASK_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('html_task.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('html_task.publlshing', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'html_taskTab', 'permissions', JText::_('COM_PRO_CRITICAL_HTML_TASK_PERMISSION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="adminform">
					<div class="adminformlist">
					<?php foreach ($this->form->getFieldset('accesscontrol') as $field): ?>
						<div>
							<?php echo $field->label; echo $field->input;?>
						</div>
						<div class="clearfix"></div>
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="html_task.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
	</div>
</div><div class="span3">
	<?php echo JLayoutHelper::render('html_task.manager_html_task_seting_rightside', $this); ?>
</div>
</form>
</div>

<script type="text/javascript">



<?php  ### /administrator/components/com_pro_critical/views/html_task/tmpl/edit.php ?>
function eventOnChangeComponentNameHTML( event  , element ){
    var $ = jQuery ;
    var Url = 'index.php?option=com_pro_critical&task=ajax.getViewsList&format=json&raw=true&<?= JSession::getFormToken() ?>=1';
    var $viewElement = $('#jform_component_view_id');
    var valComponet_id = $(element).val() ;
    if ( valComponet_id === '0' ) {
        $viewElement.empty();
        $viewElement.append( '<option value="0" selected="selected" >All View</option>' )
        return ;
    }
    var request = {
        taskElement : 'getListView' ,
        component_id : valComponet_id,
    };
    $.ajax({
        type: 'GET',
        url: Url,
        dataType: 'json',
		data: { taskData : request },
		jsonp: false ,
        success: function(data){
            $viewElement.empty();
            var newOpt  = '<option value="0" selected="selected" >All View</option>' ;
            $.each(data , function (i, obj ) {
                newOpt  = newOpt + '<option value="'+obj.id+'">'+obj.view_component+'</option>';
            });
            $viewElement.append( newOpt ).trigger("liszt:updated");
        }
    });
}
</script>
