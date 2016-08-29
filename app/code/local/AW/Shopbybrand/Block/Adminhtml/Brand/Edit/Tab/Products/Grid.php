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


class AW_Shopbybrand_Block_Adminhtml_Brand_Edit_Tab_Products_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('brandProductsGrid');
        $this->setDefaultSort('entity_id');
        $this->_controller = 'adminhtml_brand';
        $this->setUseAjax(true);
        if ($this->getBrand()->getId()) {
            $this->setDefaultFilter(array('in_products' => 1));
        }
    }

    /**
     * @return AW_Shopbybrand_Model_Brand
     */
    public function getBrand()
    {
        return Mage::registry('current_brand');
    }

    /**
     * Add filter
     *
     * @param object $column
     *
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() != 'in_products') {
            return parent::_addColumnFilterToCollection($column);
        }
        // Set custom filter for in product flag
        $productIds = $this->_getSelectedProducts();
        if (sizeof($productIds) == 0) {
            $productIds = 0;
        }

        if ($column->getFilter()->getValue()) {
            $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
        } elseif ($productIds) {
            $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
        }
        return $this;
    }

    protected function _prepareCollection()
    {
        /** @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection */
        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id')
            ->addAttributeToSelect('visibility')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('price')
            ->addAttributeToFilter('type_id', array('nin' => array('grouped', 'bundle', 'giftcard')))
        ;

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'name',
            'catalog_product/name',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'custom_name',
            'catalog_product/name',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'status',
            'catalog_product/status',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'visibility',
            'catalog_product/visibility',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'price',
            'catalog_product/price',
            'entity_id',
            null,
            'left',
            $adminStore
        );

        if ($this->getBrand()->getId()) {
            $collection->joinField(
                'position',
                'awshopbybrand/brand_product',
                'position',
                'product_id=entity_id',
                'brand_id=' . $this->getBrand()->getId(),
                'left'
            );
        }

        $this->setCollection($collection);
        parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

    /**
     * Retrieve selected brand products
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('brand_products', null);
        if (!is_array($products)) {
            $products = array_keys($this->getSelectedProducts());
        }
        return $products;
    }

    /**
     * Retrieve brand products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $products = array();
        $productsPositions = $this->getBrand()->getProductsPositions();
        foreach ($productsPositions as $position) {
            $products[$position->getProductId()] = array('position' => $position->getPosition());
        }
        return $products;
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            array(
                'header_css_class' => 'a-center',
                'type'             => 'checkbox',
                'name'             => 'products',
                'values'           => $this->_getSelectedProducts(),
                'align'            => 'center',
                'index'            => 'entity_id',
            )
        );

        $this->addColumn(
            'entity_id',
            array(
                'header'   => Mage::helper('catalog')->__('ID'),
                'sortable' => true,
                'width'    => 60,
                'index'    => 'entity_id',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('catalog')->__('Name'),
                'index'  => 'name',
            )
        );

        $productTypeOptions = Mage::getSingleton('catalog/product_type')->getOptionArray();
        unset($productTypeOptions[Mage_Catalog_Model_Product_Type::TYPE_BUNDLE]);
        unset($productTypeOptions[Mage_Catalog_Model_Product_Type::TYPE_GROUPED]);

        $this->addColumn(
            'type',
            array(
                'header'  => Mage::helper('catalog')->__('Type'),
                'width'   => 100,
                'index'   => 'type_id',
                'type'    => 'options',
                'options' => $productTypeOptions,
            )
        );

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash()
        ;

        $this->addColumn(
            'set_name',
            array(
                'header'  => Mage::helper('catalog')->__('Attrib. Set Name'),
                'width'   => 130,
                'index'   => 'attribute_set_id',
                'type'    => 'options',
                'options' => $sets,
            )
        );

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('catalog')->__('Status'),
                'width'   => 90,
                'index'   => 'status',
                'type'    => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
            )
        );

        $this->addColumn(
            'visibility',
            array(
                'header'  => Mage::helper('catalog')->__('Visibility'),
                'width'   => 90,
                'index'   => 'visibility',
                'type'    => 'options',
                'options' => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
            )
        );

        $this->addColumn(
            'sku',
            array(
                'header' => Mage::helper('catalog')->__('SKU'),
                'width'  => 80,
                'index'  => 'sku',
            )
        );

        $this->addColumn(
            'price',
            array(
                'header'        => Mage::helper('catalog')->__('Price'),
                'type'          => 'currency',
                'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
                'index'         => 'price',
            )
        );

        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('catalog')->__('Position'),
                'name'           => 'position',
                'type'           => 'number',
                'width'          => 6,
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgrid', array('_current' => true));
    }

    public function getRowUrl($item)
    {
        return '';
    }
}