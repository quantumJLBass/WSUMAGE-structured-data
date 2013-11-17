<?php
class Wsu_StructuredData_Helper_Sysinfo extends Mage_Core_Helper_Abstract {
    protected $_localCode;
    protected $_mageVersionInfo;
    // gets the localeCode from Magento
    public function getLocaleCode() {
        if (!isset($this->_localeCode)) {
            $this->_localeCode = substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2);
        }
        return $this->_localeCode;
    }

}