<?php
/**
 * StructuredData Magento Extension
 * @package Wsu_StructuredData
 * @copyright (c) 2010 Wsu, Uwe Stoll <stoll@structured_data.de>
 * @author Jeremy Bass <jeremy.bass@wsu.edu>
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>
**/
class Wsu_StructuredData_Block_Business extends Mage_Core_Block_Template
{
	// Helper
	public $rdffClassName = 'structureddata/Rdfaformat';
	private $rdff = NULL;		// rdf format helper
	// Models
	private $Business = NULL;	// Business Model
	private $GR = NULL;			// GoodRelations Ontology (gr)
	//
	private $settings;			// Wsu StructuredData Settings
	private $generalSettings;	//
	
	protected function initData()
	{
		$this->generalSettings = Mage::getStoreConfig("general");
		$this->settings = Mage::getStoreConfig("wsustructureddata");
		// Helper
		$this->rdff = Mage::app()->getHelper($this->rdffClassName);
		// Shop Models
		$this->Business = Mage::getModel('structureddata/Business');
		// Semantic Web Models
		$this->GR		= Mage::getModel('structureddata/GoodRelations');
		$this->GR->setRdff($this->rdffClassName);
	}
	
	protected function _toHtml()
	{
		@include_once("../../EssentiaLib/includeAll.php");
		
		if ($this->blockOnHomepage())
		{
			return $this->showBusinessEntity();
		}
	}
	
	protected function blockOnHomepage()
	{
		$webconfig = Mage::getStoreConfig("web");
		$homepageIdentifier = $webconfig['default']['cms_home_page'];
		$isHomepage = Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms' && Mage::getSingleton('cms/page')->getIdentifier() == $homepageIdentifier;
		return $isHomepage;
	}
	
	protected function showBusinessEntity()
	{
		$this->initData();
		if ($this->settings['basicsettings']['active'])
		{
			$html = parent::_toHtml(); 
			$this->rdff->useRdfNamespaces("rdf,rdfs,xsd,dc,owl,vcard,gr");
			$html .= $this->rdff->startRdfa($this->Business->getLegalName());
			$this->GR->setBusiness($this->Business);
			$html .= $this->GR->businessEntity();
			$html .= $this->rdff->endRdfa();
			//@webdirx_div::debug(htmlentities($html));
			return $html;
		}
	}
	
	
	/** Getter & Transformer ***********************************/
	
	
}