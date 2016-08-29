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


class AW_Shopbybrand_Block_Widget extends Mage_Core_Block_Template
{
    const DEFAULT_COLUMN_COUNT = 2;
    const DEFAULT_ROW_COUNT = 2;

    protected $_brandModel = null;
    protected $_collection = null;

    protected function _construct()
    {
        parent::_construct();
        if (!$this->getTemplate()) {
            $this->setTemplate('aw_shopbybrand/widget.phtml');
        }
        if (!$this->hasData('column_count')) {
            $this->setColumnCount(self::DEFAULT_COLUMN_COUNT);
        }
        if (!$this->hasData('row_count')) {
            $this->setRowCount(self::DEFAULT_ROW_COUNT);
        }
    }

    public function canShow()
    {
        if (null === $this->getBrand()) {
            return false;
        }
        if ($this->getBrand()->getBrandStatus()== AW_Shopbybrand_Model_Source_Status::STATUS_NOTACTIVE) {
            return false;
        }
        return true;
    }

    /**
     * @return AW_Shopbybrand_Model_Brand|null
     */
    public function getBrand()
    {
        if (null !== $this->_brandModel) {
            return $this->_brandModel;
        }
        $product = $this->getProduct();

        $brandId = $product->getData('aw_shopbybrand_brand');
        if (null === $brandId) {
            return null;
        }

        $brandModel = Mage::getModel('awshopbybrand/brand')->load($brandId);
        if (null === $brandModel->getId()) {
            return null;
        }

        $this->_brandModel = $brandModel;
        return $this->_brandModel;
    }

    public function getProduct()
    {
        if ($this->hasData('product_id')) {
            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            if (null === $product->getId()) {
                return null;
            }
            return $product;
        }
        if (Mage::registry('current_product') && Mage::registry('current_product')->getId()) {
            return Mage::registry('current_product');
        }
        return null;
    }

    public function getProductCollection()
    {
        if (null === $this->_collection) {
            $products = $this->getBrand()->getProducts();
            $products = array_diff($products, array($this->getProduct()->getId()));

            /** @var $productCollection Mage_Catalog_Model_Resource_Product_Collection */
            $productCollection = Mage::getModel('catalog/product')->getCollection();
            $productCollection->resetData();
            $productCollection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
            ;
            $productCollection
                ->addAttributeToFilter('entity_id', array('in' => $products))
                ->getSelect()->limit($this->getColumnCount() * $this->getRowCount());
            ;

            if (null !== $this->getBrand()->getProductsPositions()) {
                $productCollection->joinField(
                    'position',
                    'awshopbybrand/brand_product',
                    'position',
                    'product_id=entity_id',
                    'brand_id=' . $this->getBrand()->getId(),
                    'left'
                );
                $productCollection->setOrder('position', Varien_Data_Collection::SORT_ORDER_ASC);
            }
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($productCollection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($productCollection);
            $this->_collection = $productCollection;
        }
        return $this->_collection;
    }
}