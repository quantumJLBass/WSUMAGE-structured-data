<?php
class Wsu_StructuredData_Model_RDFs extends Mage_Core_Model_Abstract {
    // Helper
    private $rdff = NULL; // RDF Format Helper
    private $baseURL;
    public function __call($method, $args) {
        $output = "RDFs->" . $method . " (";
        foreach ($args as $arg) {
            $output .= $arg . ",";
        }
        $output .= ")";
        webdirx_div::message($output);
    }
    protected function _construct() {
        $this->_init('structureddata/rdfs');
    }
    public function setRdff($rdffClassName) {
        $this->rdff = Mage::app()->getHelper($rdffClassName);
    }
    /** RDFs ***********************************/
    public function isDefinedBy($url) {
        $rdfsIsDefinedBy = $this->rdff->rel("rdfs:isDefinedBy", $url);
        return $rdfsIsDefinedBy;
    }
    public function seeAlso($url) {
        $rdfsSeeAlso = $this->rdff->rel("rdfs:seeAlso", $url);
        return $rdfsSeeAlso;
    }
    public function label($value, $lang = "en") {
        $value          = htmlspecialchars($value);
        $replacements   = array(
            "{lang}" => $lang
        );
        #rdfs:label for gr:hasPOS
        $labelPropArray = array(
            "rdfs:label" => $value
        );
        $rdfsLabel      = $this->rdff->properties($labelPropArray, $replacements);
        return $rdfsLabel;
    }
    public function comment($value, $lang = "en") {
        $rdfsComment = $this->rdff->property("rdfs:comment", htmlspecialchars($value), NULL, $lang);
        return $rdfsComment;
    }
    public function pName($value, $lang = "en") {
        $rdfsComment = $this->rdff->property("gr:name", htmlspecialchars($value), NULL, $lang);
        return $rdfsComment;
    }
    public function pDesc($value, $lang = "en") {
        $rdfsComment = $this->rdff->property("gr:description", htmlspecialchars($value), NULL, $lang);
        return $rdfsComment;
    }
}