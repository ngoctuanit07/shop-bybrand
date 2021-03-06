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


class AW_Shopbybrand_Model_Resource_Brand_Product_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('awshopbybrand/brand_product');
    }

    public function addBrandFilter($brandId)
    {
        $this->addFieldToFilter('brand_id', array('eq' => $brandId));
        return $this;
    }

    public function addProductFilter($productId)
    {
        $this->addFieldToFilter('product_id', array('eq' => $productId));
        return $this;
    }

    public function removeAll()
    {
        if ($allIds = $this->getAllIds()) {
            $connection = $this->getConnection('core_write');
            $tableName = $this->getTable('awshopbybrand/brand_product');
            $connection->query("DELETE FROM {$tableName} WHERE id IN (" . implode(',', $allIds) . ")");
        }
        return $this;
    }
}