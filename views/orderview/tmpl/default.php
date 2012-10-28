<?php
// No direct access
defined('_JEXEC') or die('restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_article_cart/assets/css/article_cart_frontend.css');
$document->addStyleSheet('components/com_article_cart/assets/css/calender.css');
$document->addStyleSheet('components/com_article_cart/assets/css/timeentry.css');
$document->addScript( 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js' );
$jsCalender= "/components/com_article_cart/assets/js/calender.js";
$document->addScript(JURI::base(true). $jsCalender);
$timeentry= "/components/com_article_cart/assets/js/timeentry.js";
$document->addScript(JURI::base(true). $timeentry);

?>

<table class="order_view_table">
        <tr>
            <th colspan="8" class="order_view_table_title">
                <?php echo JText::_('COM_ARTICLE_CART_TITLE_ORDERS') ;?></th>
        </tr>
       <tr>
           <th><?php echo JText::_('COM_ARTICLE_CART_ORDERS_STATUS');?></th>
           <th class="order_view_title"><?php echo JText::_('COM_ARTICLE_CART_ORDERS_TITLE');?></th>
           <th><?php echo JText::_('COM_ARTICLE_CART_ORDERS_AUTHOR');?></th>
           <th><?php echo JText::_('COM_ARTICLE_CART_ORDERS_YEAR');?></th>
           <th><?php echo JText::_('COM_ARTICLE_CART_ORDERS_PAGE');?></th>
           <th><?php echo JText::_('COM_ARTICLE_CART_ORDERS_PRICE');?></th>
           <th><?php echo JText::_('COM_ARTICLE_CART_ORDERS_DOWNLOAD');?></th>
           <th><?php echo JText::_('COM_ARTICLE_CART_ORDERS_DELETE') ;?></th>
       </tr>

        <?php foreach($this->items as $item) {?>
         <tr>
            <td class="<?php if($item->status==1) echo "ready_article"; elseif($item->status==0){echo "wait_find"; } else echo "not_found";?>"></td>
            <td style="text-indent:5px;"><?php echo $item->title; ?> </td>
            <td style="text-indent:5px;"><?php echo $item->author;?></td>
            <td style="text-indent:5px;"><?php echo $item->year;?></td>
            <td style="text-indent:5px;"><?php echo $item->page;?></td>
            <td style="text-indent:5px;"><?php echo $item->price;?></td>

             <form  method="post" name="downloadfile">
                 <input name="file_name" value="<?php echo $item->file_name; ?>" type="hidden">
             <td><input type="submit" name="download" value="<?php echo JText::_('COM_ARTICLE_CART_ORDERS_DOWNLOAD');?>" <?php echo ($item->pay_id==0)?'disabled':'' ?>/></td>
             <td><input type="submit" name="delete"  value="<?php echo JText::_('COM_ARTICLE_CART_ORDERS_DELETE');?>" <?php echo ($item->pay_id!=0)?'disabled':'' ?> /></td>
                 <input type="hidden" name="delete" value="<?php echo $item->id;?>"/></td>
             </form>
        </tr>
        <?php } ?>
    <tr >
        <td colspan="8" height="20px"> </td>
    </tr>
</table>

<table class="order_view_table">
        <tr >
        <td colspan="8" height="20px">  <label class="note_label">* </label><?php echo JText::_('COM_ARTICLE_CART_ORDERS_REQUIRE');?></td>
       </tr>
        <form name="payment" method="POST">
        <tr>
            <th colspan="8" class="order_view_table_title"><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_TITLE') ;?></th>
        </tr>
        <tr>
            <th class="payment_th"><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_DATE') ;?>: <label class="note_label">*</label> </th>
            <td colspan="7" class="payment_table_row2"><input class="textEnter" type="text" id="inputField" name="payDate" maxlength="15" readonly="readonly" size="10" onmousedown="displayDatePicker('payDate');" />
                <label class="note_label"> <?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_NOTE_DATE') ;?></label>
            </td>
        </tr>
        <tr>
            <th><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_PAY_TIME') ;?>: <label class="note_label">*</label> </th>
            <td colspan="7"><input type="text" name="show24" id="show24" size="10" >
                <input type="hidden" name="payTime" id="payTime" size="10" onclick="pTime()" >
            <label class="note_label"> <?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_NOTE_TIME') ;?></label></td>
        </tr>
        <tr>
            <th ><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_AMOUNT') ;?>: <label class="note_label">*</label> </th>
            <td colspan="7">
                <select name="amount">
                    <option value="0" selected="selected"><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_SELECT_AMOUNT') ;?></option>
                    <option value="250000">250,000</option>
                    <option value="500000">500,000</option>
                    <option value="750000">750,000</option>
                    <option value="100000">1,000,000</option>
                    <option value="1250000">1,250,000</option>
                    <option value="1500000">1,500,000</option>
                    <option value="1750000">1,750,000</option>
                    <option value="2000000">2,000,000</option>
                </select>
                <?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_RIYAL') ;?>
            </td>
        </tr>
        <tr>
            <th ><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_BANK') ;?>: <label class="note_label">*</label> </th>
            <td colspan="7">
                <select name="bank">
                    <option value="none" selected="selected"><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_SELECT_BANK') ;?></option>
                    <option value="Saman"><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_SAMAN') ;?></option>
                    <option value="Melli"><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_MELLI') ;?></option>
                </select>
            </td>
        </tr>
            <tr>
                <th><?php echo JText::_('COM_ARTICLE_CART_PAYMENT_BALANCE'); ?>:</th>
                <td><?php echo number_format($this->balance) ?> <?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_RIYAL'); ?></td>
                <td colspan="5"><input name="useBalance" type="checkbox" <?php echo ($this->balance>=250000)? '' : 'disabled="disabled"' ?>"><?php echo JText::_('COM_ARTICLE_CART_PAYMENT_USE_BALANCE'); ?></td>
            </tr>
        <tr>
            <td colspan="7"><input class="Submit_button" name="submitButton" type="submit" onclick="pTime()" value="<?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_CHECK');?>"></td>
        </tr>
        </form>
    <tr>
        <td colspan="7"><?php echo JText::_('COM_ARTICLE_CART_PAYMENTS_NOTE') ;?></td>
    </tr>
    </table>


<script type="text/javascript">
    $(function () {
        $('#show24').timeEntry({show24Hours: true,showSeconds: true});

    });
    function pTime(){
        document.getElementById('payTime').value= document.getElementById('show24').value;
    }

</script>



