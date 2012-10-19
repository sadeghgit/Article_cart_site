<?php
/**
 * @version     1.0.0
 * @package     com_article_cart
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Mohammad sadegh Sarrafi <mss.sadegh@yahoo.com> - http://
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Payments list controller class.
 */
class Article_cartControllerPayments extends Article_cartController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Payments', $prefix = 'Article_cartModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}