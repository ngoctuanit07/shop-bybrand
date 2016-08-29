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


class AW_Shopbybrand_Model_Resource_Brand_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('awshopbybrand/brand');
    }

    protected function _afterLoad()
    {
        return parent::_afterLoad();
    }

    // By default use active status
    public function addStatusFilter($status = null)
    {
        if (null === $status) {
            $status = AW_Shopbybrand_Model_Source_Status::STATUS_ACTIVE;
        }
        $this->addFieldToFilter('brand_status', array('eq' => $status));
        return $this;
    }

    public function addIsShowInSidebarFilter($isShowInSidebar = null)
    {
        if (null === $isShowInSidebar) {
            $isShowInSidebar = AW_Shopbybrand_Model_Source_Yesno::CODE_YES;
        }
        $this->addFieldToFilter('is_show_in_sidebar', array('eq' => $isShowInSidebar));
        return $this;
    }

    public function addStoreFilter($id = null)
    {
        if (null === $id) {
            $id = Mage::app()->getStore()->getId();
        }

        $conditions = array(
            array("finset" => 0),
            array("finset" => $id)
        );
        $this->addFieldToFilter('store_ids', $conditions);
        return $this;
    }

    public function addUrlKeyFilter($urlKey)
    {
        $this->addFieldToFilter('url_key', $urlKey);
        return $this;
    }
}