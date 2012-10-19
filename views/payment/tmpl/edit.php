<?php
/**
 * @version     1.0.0
 * @package     com_article_cart
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Mohammad sadegh Sarrafi <mss.sadegh@yahoo.com> - http://
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$lang = JFactory::getLanguage();
$lang->load( 'com_article_cart', JPATH_ADMINISTRATOR );

?>

<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
    .front-end-edit ul {
        padding: 0 !important;
    }
    .front-end-edit li {
        list-style: none;
        margin-bottom: 6px !important;
    }
    .front-end-edit label {
        margin-right: 10px;
        display: block;
        float: left;
        width: 200px !important;
    }
    .front-end-edit .radio label {
        display: inline;
        float: none;
    }
    .front-end-edit .readonly {
        border: none !important;
        color: #666;
    }    
    .front-end-edit #editor-xtd-buttons {
        height: 50px;
        width: 600px;
        float: left;
    }
    .front-end-edit .toggle-editor {
        height: 50px;
        width: 120px;
        float: right;
        
    }
</style>

<div class="payment-edit front-end-edit">
    <h1>Edit <?php echo $this->item->id; ?></h1>

    <form id="form-payment" action="<?php echo JRoute::_('index.php?option=com_article_cart&task=payment.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
        <ul>
        
        <li><?php echo $this->form->getLabel('id'); ?>
        <?php echo $this->form->getInput('id'); ?></li>

        
        <li><?php echo $this->form->getLabel('created_by'); ?>
        <?php echo $this->form->getInput('created_by'); ?></li>

        
        <li><?php echo $this->form->getLabel('receipt_no'); ?>
        <?php echo $this->form->getInput('receipt_no'); ?></li>

        
        <li><?php echo $this->form->getLabel('reference_no'); ?>
        <?php echo $this->form->getInput('reference_no'); ?></li>

        
        <li><?php echo $this->form->getLabel('pay_date'); ?>
        <?php echo $this->form->getInput('pay_date'); ?></li>

        
        <li><?php echo $this->form->getLabel('pay_time'); ?>
        <?php echo $this->form->getInput('pay_time'); ?></li>

        
        <li><?php echo $this->form->getLabel('amount'); ?>
        <?php echo $this->form->getInput('amount'); ?></li>

        
        <li><?php echo $this->form->getLabel('bank'); ?>
        <?php echo $this->form->getInput('bank'); ?></li>

        
        <li><?php echo $this->form->getLabel('claim'); ?>
        <?php echo $this->form->getInput('claim'); ?></li>

        

        <li><?php echo $this->form->getLabel('state'); ?>
                    <?php echo $this->form->getInput('state'); ?></li><li><?php echo $this->form->getLabel('checked_out'); ?>
                    <?php echo $this->form->getInput('checked_out'); ?></li><li><?php echo $this->form->getLabel('checked_out_time'); ?>
                    <?php echo $this->form->getInput('checked_out_time'); ?></li>
    
        </ul>
		<div>
			<button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
			<?php echo JText::_('or'); ?>
			<a href="<?php echo JRoute::_('index.php?option=com_article_cart&task=payment.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

			<input type="hidden" name="option" value="com_article_cart" />
			<input type="hidden" name="task" value="payment.save" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
