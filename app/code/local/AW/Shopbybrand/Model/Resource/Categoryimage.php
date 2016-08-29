<?php
class AW_Shopbybrand_Model_Resource_Categoryimage extends Mage_Core_Model_Mysql4_Abstract
{
     public function _construct()
     {
     	$this->_init('awshopbybrand/categoryimage','id');
     }
}