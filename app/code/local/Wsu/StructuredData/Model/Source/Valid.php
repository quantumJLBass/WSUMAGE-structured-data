<?php

class Wsu_StructuredData_Model_Source_Valid
{
	public function toOptionArray()
	{
		$vPeriods = array();
		$vPeriods[] = array('value'=>'0', 'label'=>'Disabled');
		$vPeriods[] = array('value'=>'3', 'label'=>'3 months');
		$vPeriods[] = array('value'=>'6', 'label'=>'6 months');
		$vPeriods[] = array('value'=>'12', 'label'=>'12 months');
		return $vPeriods;
	}
}