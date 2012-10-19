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
?>

<?php if( $this->item ) : ?>

    <div class="item_fields">
        
        <ul class="fields_list">

        
        
            <li><?php echo 'id'; ?>: 
            <?php echo $this->item->id; ?></li>

        
        
            <li><?php echo 'ordering'; ?>: 
            <?php echo $this->item->ordering; ?></li>

        
        
            <li><?php echo 'state'; ?>: 
            <?php echo $this->item->state; ?></li>

        
        
            <li><?php echo 'checked_out'; ?>: 
            <?php echo $this->item->checked_out; ?></li>

        
        
            <li><?php echo 'checked_out_time'; ?>: 
            <?php echo $this->item->checked_out_time; ?></li>

        
        
            <li><?php echo 'created_by'; ?>: 
            <?php echo $this->item->created_by; ?></li>

        
        
            <li><?php echo 'receipt_no'; ?>: 
            <?php echo $this->item->receipt_no; ?></li>

        
        
            <li><?php echo 'reference_no'; ?>: 
            <?php echo $this->item->reference_no; ?></li>

        
        
            <li><?php echo 'pay_date'; ?>: 
            <?php echo $this->item->pay_date; ?></li>

        
        
            <li><?php echo 'pay_time'; ?>: 
            <?php echo $this->item->pay_time; ?></li>

        
        
            <li><?php echo 'amount'; ?>: 
            <?php echo $this->item->amount; ?></li>

        
        
            <li><?php echo 'bank'; ?>: 
            <?php echo $this->item->bank; ?></li>

        
        
            <li><?php echo 'claim'; ?>: 
            <?php echo $this->item->claim; ?></li>

        

        </ul>
        
    </div>
    <?php if(JFactory::getUser()->authorise('core.edit', 'com_article_cart.payment'.$this->item->id)): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_article_cart&task=payment.edit&id='.$this->item->id); ?>">Edit</a>
	<?php endif; ?>
<?php else: ?>
    Could not load the item
<?php endif; ?>
