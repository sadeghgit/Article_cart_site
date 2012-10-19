<?php
//no direct access
defined('_JEXEC') or die('restricted access');
//import joomla view library
jimport('joomla.application.component.view');

//HTML view class for setorder form

class Article_cartViewSetOrder extends Jview{

    function display($tpl=NULL){


        $model=&$this->getModel();
        $readForm =$model->readForm();
        //list($title,$author,$year,$page)=$readForm;
        //$this->assignRef('title',$title);
       // $this->assignRef('author',$author);
        //$this->assignRef('year',$year);
       // $this->assignRef('page',$page);



        parent::display($tpl);
    }
}
