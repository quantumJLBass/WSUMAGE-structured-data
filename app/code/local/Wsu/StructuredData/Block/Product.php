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
class Wsu_StructuredData_Block_Product extends Mage_Catalog_Block_Product_Abstract
{
	// Helper
	public $rdffClassName = 'structureddata/Rdfaformat';
	private $rdff = NULL;		// rdf format helper
	// Models
    private $Business = NULL;
	private $Product = NULL;
	private $GR = NULL;			// GoodRelations Ontology (gr)
	// 
	private $settings;			// Wsu StructuredData Settings
	private $generalSettings;	// 
	
    
	public function __construct()
    {
    	parent::__construct();
    }
    
	protected function initData()
	{
		$this->generalSettings = Mage::getStoreConfig("general");
		$this->settings = Mage::getStoreConfig("wsustructureddata");
		// Helpre
		$this->rdff = $this->helper($this->rdffClassName);
		// Shop Models
		$this->Business = Mage::getModel('structureddata/Business');
		$this->Product = $this->getProduct();
		// Semantic Web Models
		$this->GR		= Mage::getModel('structureddata/GoodRelations');
		$this->GR->setRdff($this->rdffClassName);
	}
    
	protected function _toHtml()
	{
		$html = "\n<!-- WSU Structured Data -->"; 
		$html .="\n"; 
		$id = Mage::registry('current_product')->getId();;
		$product = Mage::getModel('catalog/product')->load($id);
   		
   		if ($product->getTypeId() == "grouped") {
   			$html .= "<!-- Magento grouped products are not supported by MSemantic at the moment.-->";
   		}else {
		@include_once("../../EssentiaLib/includeAll.php");
			$this->initData();
			if ($this->settings['basicsettings']['active'])
			{
				$html .= parent::_toHtml();
				$this->rdff->useRdfNamespaces("rdf,rdfs,xsd,dc,owl,vcard,gr,product,v,foaf,media");
				$html .= $this->rdff->startRdfa($this->Business->getLegalName());
				$this->GR	->setBusiness($this->Business)
							->setProduct($this->Product);
				$html .= $this->GR->pOffering();
				$html .= $this->rdff->endRdfa();
				//@webdirx_div::debug(htmlentities($html));	
			}
		}
		$html .= "\n";
		$html .= "<!-- WSU Structured Data -->\n";
		return $html;
		
	}
	
}