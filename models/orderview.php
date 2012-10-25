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

        $user=JFactory::getUser();
        //fetch current user id
        $user_id=$user->id;
        //connect to the database
        $db=JFactory::getDBO();
        // new object of query
        $query = $db->getQuery(true);
        // Select all fields
        $query->select('*');
        // From the __article_cart_orders table
        $query->from('`#__article_cart_orders`');
        //where create_by user
        $query->where("`created_by`='".$user_id."'");
        //run the query
        $db->setQuery($query);
        // check the query result
        if(!$resultquery=$db->query())  echo'no result found';
        else    $items = ($items = $db->loadObjectList())?$items:array();//create a array of found record(s)

        //return the array to view.html.php
        return $items;
    }//end orderview



    //check for payment data
    function checkPayment(){
        global $payTime,$amount,$bank,$msgSucc;
        if(isset($_POST['useBalance'])){
           $amount = $this->userBalance();
            $user=JFactory::getUser();
            //fetch current user id
            $pay_id=$user->id;
            $this->setPayment($pay_id,$amount);
        }else{
            //variable to include html script
            $msgSucc="";
            $msgSucc .="<table>";
            //check submit button clicked
            if (isset($_POST['payDate'])){
                //receive the fields data and a safe manner
                $payDate=mysql_real_escape_string($_POST['payDate']);
                //convert date format received from date picker to a format acceptable for database
                //split the date to three variables
                list($m,$d,$y)=explode('/',$payDate);
                //convert it to date and time format
                $mk=mktime(0,0,0,$m,$d,$y);
                //change the format of datetime string
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
                    // count the number of item fetch from database
                    $m=count($items);
                    if($m==1){
                    //create new object of data
                    $data=$items[$m-1];
                    //get the field id from new object
                    $pay_id=$data->id;
                        // send the Payment id and the amount to the setpayment function
                    $this->setPayment($pay_id,$amount);
                    }else{
                        //end of html script
                       echo $msgSucc .="<tr><td class=\"form_massage_error\">". JText::_('COM_ARTICLE_CART_PAYMENT_DETAIL_ERROR')."</td></tr></table>";
                    }
                }
            }
        }
    }

    //update the payment table for the claimed payment
    function setPayment($pay_id,$amount){
       global $noOrder;
        //the default price of each item would be 250000, there this shows the payment is for how many items
        $limit=$amount/250000;
        $user=JFactory::getUser();
        $user_id=$user->id;
        $db=JFactory::getDBO();
        $query=$db->getQuery(true);
        //update query for the order table set the payment id for the order to make download button active
        $query->update('`#__article_cart_orders`');
        $query->set("`pay_id`='".$pay_id."'");
        $query->where("`created_by`='".$user_id."' and `pay_id`=0 and `status`=1 LIMIT ".$limit);
        $db->setQuery($query);
        if(!$resultquery=$db->query())  echo'update decline';
        else {
            // the number of affected rows will help to find out the amount has set for how many ordered item and
            // whether is any balance
            $noOrder= mysql_affected_rows();
            //echo 'payment saved';
           // $query->update('`#__article_cart_payments`');
           // $query->set("`claim`='1'");
           // $query->where("`id`='".$pay_id."'");
            //needs to update the order to be omited from unclaimed payments
            $query="UPDATE `#__article_cart_payments` SET `claim`=1 WHERE `id`='".$pay_id."'";
            $db->setQuery($query);
            if(!$resultquery=$db->query())  echo'claim decline';
             else{
               echo "<table><tr><td class=\"form_massage_payment\" >". JText::_('COM_ARTICLE_CART_PAYMENT_CLAIM')."</td></tr></table>";
                 //calculate the balance of payment
                 $this->setBalance($limit,$noOrder);
             }


        }

    }

    function userBalance(){

        $user=JFactory::getUser();
        //fetch current user id
        $user_id=$user->id;
        //connect to the database
        $db=JFactory::getDBO();
        // new object of query
        $query = $db->getQuery(true);

        $query->select('`account_balance`');
        // From the __article_cart_orders table
        $query->from('`#__users`');
        //where create_by user
        $query->where("`id`='".$user_id."'");
        //run the query
        $db->setQuery($query);
        // check the query result
        if(!$resultquery=$db->query())  echo'no result found';
        else {
            $items = ($items = $db->loadObjectList())?$items:array();
            // count the number of item fetch from database
            $m=count($items);
            if($m==1){
                //create new object of data
                $data=$items[$m-1];
                //get the field id from new object
                $balance=$data->account_balance;
            }
        }

        return $balance;
    }

    function setBalance($limit,$noOrder){
        $balance=($limit-$noOrder)*250000;
        if ($balance>0){
            $user=JFactory::getUser();
            $user_id=$user->id;
            $db=JFactory::getDBO();
            $query=$db->getQuery(true);
            $userBalance =$this->userBalance();
            (isset($_POST['useBalance'])) ?$balance=$balance :$balance=$balance+$userBalance;

            //updating the user table for the new balance
            $query->update('#__users');
            $query->set("`account_balance`='".$balance."'");
            $query->where("`id`='".$user_id."'");
            $db->setQuery($query);
            if(!$resultquery=$db->query()) echo 'error in update';
            else echo "<table><tr><td class=\"form_massage_payment\" >". JText::_('COM_ARTICLE_CART_PAYMENT_BALANCE').$balance.JText::_('COM_ARTICLE_CART_PAYMENT_BALANCE_C')."</td></tr></table>";
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
