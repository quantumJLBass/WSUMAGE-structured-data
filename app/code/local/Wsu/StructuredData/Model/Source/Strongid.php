<?php

class Wsu_StructuredData_Model_Source_Strongid
{
	public function toOptionArray()
	{
		$strongid = array();
		$strongid[] = array('value'=>'gtin8', 'label'=>'GTIN-8 / EAN_UCC-8');
		$strongid[] = array('value'=>'gtin14', 'label'=>'GTIN-14');
		$strongid[] = array('value'=>'ean13', 'label'=>'EAN_UCC-13');
		return $strongid;
	}
}