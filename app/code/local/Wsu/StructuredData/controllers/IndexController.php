<?php
 /**
 * StructuredData Magento Extension
 * @package Wsu_StructuredData
 * @copyright (c) 2013+ Jeremy Bass
 * @author Jeremy Bass <jeremy.bass@wsu.edu>
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
**/

class Wsu_StructuredData_IndexController extends Mage_Core_Controller_Front_Action
{
	//public function indexAction()
	//{
		// disabled <-> danger of a ddos attack
		/**
		$this->getResponse()->setHeader('Content-type', 'application/rdf+xml');
		
		$this->loadLayout();
		$this->getLayout()
			->getBlock('root')
			->setTemplate("wsu/dump.phtml");		// change
		$this	->getLayout()
				->getBlock('content')->append(
					$this->getLayout()->createBlock('wsu_structureddata/datadump')
				);
		$this->renderLayout();
		*/
	//}
}
