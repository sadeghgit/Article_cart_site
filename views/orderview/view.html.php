<?php
// No direct access
defined('_JEXEC') or die('restricted access');

//import joomla view library
jimport('joomla.application.component.view');

/**
 * HTML view class for article orderview component
 */
class article_cartViewOrderView extends Jview{

    function display($tpl= NULL){
        //set $model as new model object
        $model = &$this->getModel();
        //call for the orderDisplay function in model
        $orderView = $model->orderDisplay();
        //assign the return value of the function(which is a list of record) to the item index in for default.php for view
        $this->assignRef( 'items', $orderView );

        $userBalance =$model->userBalance();
        $this->assignRef('balance',$userBalance);
        //call for cjecjPayment function
        $checkPayment=$model->checkPayment();
        $model->download();
        $model->deleteOrder();
        $orderView = $model->orderDisplay();
        //assign the return value of the function(which is a list of record) to the item index in for default.php for view
        $this->assignRef( 'items', $orderView );
        $userBalance = $model->userBalance();
        //assign the return value of the function(which is a list of record) to the item index in for default.php for view
        $this->assignRef( 'balance', $userBalance );

        parent::display($tpl);
    }

}