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


class AW_Shopbybrand_Block_Navigation extends Mage_Core_Block_Template
{
    /**
     * @return AW_Shopbybrand_Model_Resource_Brand_Collection
     */
    public function getBrandCollection()
    {
        /** @var $brandsCollection AW_Shopbybrand_Model_Resource_Brand_Collection */
        $brandsCollection = Mage::getModel('awshopbybrand/brand')->getCollection();
        $brandsCollection
            ->addStatusFilter()
            ->setOrder('priority', Varien_Data_Collection::SORT_ORDER_ASC)
        ;
        return $brandsCollection;
    }

    public function getAllBrandsUrl()
    {
        $allBrandsPageKey = Mage::helper('awshopbybrand/config')->getAllBrandsUrlKey();
        return Mage::getBaseUrl() . $allBrandsPageKey;
    }

    public function getAllBrandsTitle()
    {
        return Mage::helper('awshopbybrand/config')->getAllBrandsTitle();
    }

    public function getCategoriesMenu()
    {
        if (
            !Mage::helper('awshopbybrand')->isModuleEnabled()
            || !Mage::helper('awshopbybrand/config')->getIsAddToMenu()
        ) {
            return false;
        }

        $brandCollection = $this->getBrandCollection();
        if ($brandCollection->getSize() <= 0) {
            return false;
        }
        return $brandCollection;
    }
}