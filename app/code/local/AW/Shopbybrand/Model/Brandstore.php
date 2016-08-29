<?php
class AW_Shopbybrand_Model_Brandstore extends Mage_Core_Model_Abstract
{
     public function _construct()
     {
         parent::_construct();
         $this->_init('awshopbybrand/brandstore');
     }
}