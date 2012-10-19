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

        $model = &$this->getModel();
        $orderView = $model->orderDisplay();
        $this->assignRef( 'items', $orderView );
        $checkPayment=$model->checkPayment();

        parent::display($tpl);
    }

}