<?php
class AW_Shopbybrand_Model_Resource_Brandstore_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
     public function _construct()
     {
     	parent::_construct();
     	$this->_init('awshopbybrand/brandstore');
     }
}