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


class AW_Shopbybrand_Helper_Config extends Mage_Core_Helper_Abstract
{
    const GENERAL_ADD_TO_MENU                        = 'awshopbybrand/general/addtomenu';

    const BRAND_LIST_SETUP_DISPLAY_MODE              = 'awshopbybrand/brands_list_setup/brands_list_display_mode';

    const PRODUCT_PAGE_SETUP_DISPLAY_MODE            = 'awshopbybrand/product_page_setup/product_page_display_mode';

    const ALL_BRANDS_PAGE_SETUP_URL_KEY              = 'awshopbybrand/all_brands_page_setup/url_key';
    const ALL_BRANDS_PAGE_SETUP_TITLE                = 'awshopbybrand/all_brands_page_setup/title';
    const ALL_BRANDS_PAGE_SETUP_META_KEYWORDS        = 'awshopbybrand/all_brands_page_setup/meta_keywords';
    const ALL_BRANDS_PAGE_SETUP_META_DESCRIPTION     = 'awshopbybrand/all_brands_page_setup/meta_description';
    const ALL_BRANDS_PAGE_SETUP_ALL_PAGE_DESCRIPTION = 'awshopbybrand/all_brands_page_setup/al_brands_page_description';

    const ALL_BRANDS_PAGE_KEY                        = 'shopbybrands';
    const ALL_BRANDS_TITLE                           = 'All brands';

    public function getIsAddToMenu($store = null)
    {
        return Mage::getStoreConfig(self::GENERAL_ADD_TO_MENU, $store);
    }

    public function getBrandListDisplayMode($store = null)
    {
        return Mage::getStoreConfig(self::BRAND_LIST_SETUP_DISPLAY_MODE, $store);
    }

    public function getProductPageDisplayMode($store = null)
    {
        return Mage::getStoreConfig(self::PRODUCT_PAGE_SETUP_DISPLAY_MODE, $store);
    }

    public function getAllBrandsUrlKey($store = null)
    {
        $allBrandsPageKey = Mage::getStoreConfig(self::ALL_BRANDS_PAGE_SETUP_URL_KEY, $store);
        if (!$allBrandsPageKey) {
            $allBrandsPageKey = self::ALL_BRANDS_PAGE_KEY;
        }
        return $allBrandsPageKey;
    }

    public function getAllBrandsTitle($store = null)
    {
        $title = Mage::getStoreConfig(self::ALL_BRANDS_PAGE_SETUP_TITLE, $store);
        if (!$title) {
            $title = self::ALL_BRANDS_TITLE;
        }
        return $title;
    }

    public function getAllBrandsMetaKeywords($store = null)
    {
        return Mage::getStoreConfig(self::ALL_BRANDS_PAGE_SETUP_META_KEYWORDS, $store);
    }

    public function getAllBrandsMetaDescription($store = null)
    {
        return Mage::getStoreConfig(self::ALL_BRANDS_PAGE_SETUP_META_DESCRIPTION, $store);
    }

    public function getAllPagesDescription($store = null)
    {
        return Mage::getStoreConfig(self::ALL_BRANDS_PAGE_SETUP_ALL_PAGE_DESCRIPTION, $store);
    }
}