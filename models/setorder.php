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
 * Method supporting entering new records
 */

class Article_cartModelSetOrder extends JModelList{

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


    //retrieve data from form and store in variables
    function readForm(){
        global $title,$author,$year,$page,$link;
        //check submit button pressed
        if (isset($_POST['submitForm'])){
            //get each field of the form
            $title=mysql_real_escape_string($_POST['title']);
            $author=mysql_real_escape_string($_POST['author']);
            $year=mysql_real_escape_string($_POST['year']);
            $pageStart=mysql_real_escape_string($_POST['pageStart']);
            $pageEnd=mysql_real_escape_string($_POST['pageEnd']);
            $page=$pageStart.'-'.$pageEnd;
            $link='';
            //set to validation function for validation
            $this->validate($title,$author,$year,$page,$pageStart,$pageEnd,$link);
        }elseif(isset($_POST['submitLink'])){
            $link=mysql_real_escape_string($_POST['link']);
            $title=$link;
            $author='N/A';
            $year='';
            $pageStart='';
            $pageEnd='';
            $page='';
            if(!preg_match("/^[_\.0-9a-zA-Z-]/i", $link))
                echo "<table><tr class=\"order_form_error_message_border\" ><td class=\"order_form_error_message \">"."* ". JText::_('COM_ARTICLE_CART_ORDERS_LINK_ERROR')."</td></tr></table>";
            else  $this->setRecord($title,$author,$year,$page,$link);

        }
        //no need if we don't want to show the data of the fields after form error
        return array($title,$author,$year,$page);
    }


    //validate data
    function validate($title,$author,$year,$page,$pageStart,$pageEnd,$link){
         // they must be change to better validation, this are temprory
        $flag="OK";   // This is the flag and we set it to OK
        $msg ="";
        $msg .="<table>";
        if(!preg_match("/^[_\.0-9a-zA-Z-]/i", $title)){    // checking the
            $msg .="<tr class=\"order_form_error_message_border\" ><td class=\"order_form_error_message \">"."* ". JText::_('COM_ARTICLE_CART_ORDERS_TITLE_ERROR')."</td></tr>";
            $flag="NOTOK";   //setting the flag to error flag.
        }
        if(!preg_match("/^[_\.0-9a-zA-Z-]/i", $author)){ // checking the
            $msg .="<tr class=\"order_form_error_message_border\" ><td class=\"order_form_error_message \">"."* ". JText::_('COM_ARTICLE_CART_ORDERS_AUTHOR_ERROR')."</td></tr>";
            $flag="NOTOK";   //setting the flag to error flag.
        }
        if(!is_numeric($year)){    // checking
            //$msg .= "<table width=\"800\" height=\"159\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#2089b6\" >";
            $msg .="<tr class=\"order_form_error_message_border\" ><td class=\"order_form_error_message \">"."* ". JText::_('COM_ARTICLE_CART_ORDERS_YEAR_ERROR')."</td></tr>";
            $flag="NOTOK";   //setting the flag to error flag.
        }
        if(!is_numeric($pageStart)|| $pageStart<1 ){    // checking
            //$msg .= "<table width=\"800\" height=\"159\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#2089b6\" >";
            $msg .="<tr class=\"order_form_error_message_border\" ><td class=\"order_form_error_message \">"."* ". JText::_('COM_ARTICLE_CART_ORDERS_PAGE_START_ERROR')."</td></tr>";
            $flag="NOTOK";   //setting the flag to error flag.
        }
        if(!is_numeric($pageEnd)|| $pageEnd<1){    // checking
            //$msg .= "<table width=\"800\" height=\"159\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#2089b6\" >";
            $msg .="<tr class=\"order_form_error_message_border\" ><td class=\"order_form_error_message \">"."* ". JText::_('COM_ARTICLE_CART_ORDERS_PAGE_END_ERROR')."</td></tr>";
            $flag="NOTOK";   //setting the flag to error flag.
        }
        if($flag <>"OK"){
            $msg .="</table>";
            echo $msg;
        }else{
            //send the valiables to insert to the database

            $this->setRecord($title,$author,$year,$page,$link);
        }
    }




    function setRecord($title,$author,$year,$page,$link){

        global $msgSucc;
        $user= JFactory::getUser();
        $user_id=$user->id;

        $db=JFactory::getDBO();
        //insert the data in order table
        $query="INSERT INTO `#__article_cart_orders` (created_by,title,author,year,page,link)VALUES('".$user_id."',
        '".$title."','".$author."','".$year."','".$page."','".$link."')";
        $msgSucc="";
        $msgSucc .="<table>";
        $db->setQuery($query);
        if (!$resultquery = $db->query()) {
           // echo $db->stderr();
            $msgSucc .="<tr><td class=\"form_massage_error\" >". JText::_('FAILED SUBMIT')."</td></tr>";
            return false;
        }else{
            $msgSucc .="<tr><td  class=\"form_massage_payment\" >". JText::_('COM_ARTICLE_CART_ORDERS_SUCCESS_SUBMIT')."</td></tr></table>";
            }
        echo $msgSucc;
    }

}