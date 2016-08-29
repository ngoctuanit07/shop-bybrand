<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This software is designed to work with Magento community edition and
 * its use on an edition other than specified is prohibited. aheadWorks does not
 * provide extension support in case of incorrect edition use.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Shopbybrand
 * @version    1.3.2
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */



class AW_Shopbybrand_Model_Attribute_Brand_Source extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve all attribute options
     * @return array
     */
    public function getAllOptions()
    {
        $options = Mage::getSingleton('awshopbybrand/source_attribute')->getBrandsToSelect();
        $brands = array(array('value' => 0, 'label' => Mage::helper('awshopbybrand')->__('Not defined')));
        foreach ($options as $option) {
            array_push($brands, array('value' => $option->getId(), 'label' => $option->getTitle()));
        }
        return $brands;
    }
}