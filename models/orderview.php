<?php

/**
* @version     1.0.0
* @package     com_article_cart
* @copyright   Copyright (C) 2012. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
* @author      Mohammad sadegh Sarrafi <mss.sadegh@yahoo.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Article_cart records.
 */
class Article_cartModelOrderView extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    //display orders
    function orderDisplay() {
        global $title,$author,$year,$page,$status,$pay_id,$download;
        $user=JFactory::getUser();
        $user_id=$user->id;
        $db=JFactory::getDBO();
        $query = $db->getQuery(true);
        // Select all fields
        $query->select('*');
        // From the __article_cart_orders table
        $query->from('`#__article_cart_orders`');
        //where create_by user
        $query->where("`created_by`='".$user_id."'");

        $db->setQuery($query);
        if(!$resultquery=$db->query())  echo'no result found';
        else    $items = ($items = $db->loadObjectList())?$items:array();

        return $items;
    }//end orderview


    //check for payment data
    function checkPayment(){
            global $refNo,$recNo,$payTime,$amount,$bank,$msgSucc;
            $msgSucc="";
            $msgSucc .="<table>";
            if (isset($_POST['amount'])){
                $payDate=mysql_real_escape_string($_POST['payDate']);
                list($m,$d,$y)=explode('/',$payDate);
                $mk=mktime(0,0,0,$m,$d,$y);
                $payDate=strftime('%Y-%m-%d',$mk);
                $payTime=mysql_real_escape_string($_POST['payTime']);
                $amount=mysql_real_escape_string($_POST['amount']);
                $bank=mysql_real_escape_string($_POST['bank']);

                $db=JFactory::getDBO();
                $query = $db->getQuery(true);
                // Select all fields
                $query->select('*');
                // From the __article_cart_orders table
                $query->from('`#__article_cart_payments`');
                //where create_by user
                $query->where("`pay_date`='".$payDate."' and `pay_time`='".$payTime."' and `amount`='".$amount."' and `bank`='".$bank."' and `claim`=0");
                //$query="SELECT * FROM `#__article_cart_payments` WHERE receipt_no='".$recNo."' or reference_no='".$refNo."' or pay_time='".$payTime."' and amount='".$amount."' and bank='".$bank."'";

                $db->setQuery($query);
                if(!$resultquery=$db->query())  echo'no result found';
                else {
                    $items = ($items = $db->loadObjectList())?$items:array();
                    $m=count($items);
                    if($m==1){
                    $data=$items[$m-1];
                    $pay_id=$data->id;
                    $this->setPayment($pay_id,$amount);
                    }else{
                       echo $msgSucc .="<tr><td class=\"form_massage_error\">". JText::_('COM_ARTICLE_CART_PAYMENT_DETAIL_ERROR')."</td></tr></table>";
                    }
                }
            }
    }


    function setPayment($pay_id,$amount){
       global $msgSucc,$noRecord,$balance;
        $msgSucc=" ";
        $msgSucc ="<table>";
        $limit=$amount/250000;
        $user=JFactory::getUser();
        $user_id=$user->id;
        $db=JFactory::getDBO();
        $query=$db->getQuery(true);
        $query->update('`#__article_cart_orders`');
        $query->set("`pay_id`='".$pay_id."'");
        $query->where("`created_by`='".$user_id."' and `pay_id`=0 and `status`=1 LIMIT ".$limit);
        $db->setQuery($query);
        if(!$resultquery=$db->query())  echo'update decline';
        else {
            //echo 'payment saved';
           // $query->update('`#__article_cart_payments`');
           // $query->set("`claim`='1'");
           // $query->where("`id`='".$pay_id."'");
            $query="UPDATE `#__article_cart_payments` SET `claim`=1 WHERE `id`='".$pay_id."'";
            $db->setQuery($query);
            $noRecord= mysql_affected_rows();
            if(!$resultquery=$db->query())  echo'claim decline';
             else{
               $msgSucc .="<tr><td class=\"form_massage_payment\" >". JText::_('COM_ARTICLE_CART_PAYMENT_CLAIM')."</td></tr>";
                 $balance=($limit-$noRecord)*250000;
                 if ($balance>0){
                     $user=JFactory::getUser();
                     $user_id=$user->id;
                     $db=JFactory::getDBO();
                     $query=$db->getQuery(true);
                     $query->update('#__users');
                     $query->set("`account_balance`='".$balance."'");
                     $query->where("`id`='".$user_id."'");
                     $db->setQuery($query);
                     if(!$resultquery=$db->query()) echo 'error in update';
                     else  $msgSucc .="<tr><td class=\"form_massage_payment\" >". JText::_('COM_ARTICLE_CART_PAYMENT_BALANCE').$balance.JText::_('COM_ARTICLE_CART_PAYMENT_BALANCE_C')."</td></tr></table>";
                 }
                 echo $msgSucc .="</table>";
             }


        }


    }




    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {

        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $this->setState('list.start', $limitstart);

        // List state information.
        parent::populateState();
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__article_cart_orders` AS a');



            // Filter by published state
            $published = $this->getState('filter.state');
            if (is_numeric($published)) {
                $query->where('a.state = '.(int) $published);
            } else if ($published === '') {
                $query->where('(a.state IN (0, 1))');
            }
            /********************
             *
             *
             *
             */





        return $query;
    }

}
