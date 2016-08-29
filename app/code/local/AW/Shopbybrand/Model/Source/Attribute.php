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


class AW_Shopbybrand_Model_Source_Attribute
{
    const ATTRIBUTE_CODE = 'aw_shopbybrand_brand';
    const PRODUCT_TYPES = 'simple,configurable,virtual,downloadable';

    private $_installer = null;

    private function _initInstaller()
    {
        if (!$this->_installer) {
            $this->_installer = new Mage_Eav_Model_Entity_Setup('core_setup');
        }
    }

    public function removeAttribute()
    {
        $this->_initInstaller();
        $this->_installer->removeAttribute('catalog_product', self::ATTRIBUTE_CODE);
    }

    public function installAttribute()
    {
        $this->_initInstaller();
        $this->_installer->addAttribute(
            'catalog_product',
            self::ATTRIBUTE_CODE,
            array(
                'type'                          => 'int',
                'backend'                       => 'awshopbybrand/attribute_brand_backend',
                'source'                        => 'awshopbybrand/attribute_brand_source',
                'frontend'                      => 'awshopbybrand/attribute_brand_frontend',
                'label'                         => 'Brand',
                'searchable'                    => true,
                'filterable'                    => true,
                'group'                         => 'Product Brand',
                'input'                         => 'select',
                'class'                         => 'validate-digit',
                'global'                        => true,
                'visible'                       => true,
                'required'                      => false,
                'user_defined'                  => false,
                'default'                       => '0',
                'visible_on_front'              => false,
                'is_used_for_promo_rules'       => true,
                'is_visible_in_advanced_search' => true,
                'is_filterable_in_search'       => true,
            )
        );
        $this
            ->_installer
            ->updateAttribute('catalog_product', self::ATTRIBUTE_CODE, 'apply_to', self::PRODUCT_TYPES)
        ;
        $this
            ->_installer
            ->updateAttribute('catalog_product', self::ATTRIBUTE_CODE, 'is_filterable', true)
        ;
        $this
            ->_installer
            ->updateAttribute('catalog_product', self::ATTRIBUTE_CODE, 'is_searchable', true)
        ;
        $this
            ->_installer
            ->updateAttribute('catalog_product', self::ATTRIBUTE_CODE, 'is_used_for_promo_rules', true)
        ;
        $this
            ->_installer
            ->updateAttribute('catalog_product', self::ATTRIBUTE_CODE, 'is_visible_in_advanced_search', true)
        ;
    }

    public function getBrandsToSelect()
    {
        /** @var $brands AW_Shopbybrand_Model_Resource_Brand_Collection */
        $brands = Mage::getModel('awshopbybrand/brand')->getCollection();
        $brands->addFieldToFilter('brand_status', array('eq' => AW_Shopbybrand_Model_Source_Status::STATUS_ACTIVE));
        return $brands;
    }

    public function updateProductAttributeByName($attributeName, $value)
    {
        $this->_initInstaller();
        $this
            ->_installer
            ->updateAttribute('catalog_product', self::ATTRIBUTE_CODE, $attributeName, $value)
        ;
    }
}