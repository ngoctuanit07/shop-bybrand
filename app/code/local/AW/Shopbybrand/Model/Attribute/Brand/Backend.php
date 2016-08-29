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


class AW_Shopbybrand_Model_Attribute_Brand_Backend extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    public function afterSave($object)
    {
        parent::afterSave($object);

        if ($previousBrandId = $object->getOrigData($this->getAttribute()->getAttributeCode())) {
            $brandModel = Mage::getModel('awshopbybrand/brand')->load($previousBrandId);
            $productsIds = explode(',', $brandModel->getData('products'));
            if (in_array($object->getId(), $productsIds)) {
                return;
            }
            //remove product from previous brand model
            if (null !== $brandModel->getId()) {
                $brandModel
                    ->removeProduct($object->getId())
                    ->setSource('attribute')
                    ->save()
                ;
            }

            //add product to chosen brand model
            $currentBrandId = $object->getData($this->getAttribute()->getAttributeCode());
            $brandModel = Mage::getModel('awshopbybrand/brand')->load($currentBrandId);
            if (null !== $brandModel->getId()) {
                $brandModel
                    ->addProduct($object->getId())
                    ->setSource('attribute')
                    ->save()
                ;
            }
        }
    }
}