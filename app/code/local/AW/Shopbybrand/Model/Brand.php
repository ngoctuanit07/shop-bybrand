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


class AW_Shopbybrand_Model_Brand extends Mage_Core_Model_Abstract
{
    const IMAGE_WIDTH = 150;
    const IMAGE_HEIGHT = 150;
    const ICON_WIDTH = 50;
    const ICON_HEIGHT = 50;

    const DEFAULT_IMAGE_PATH = '/images/catalog/product/placeholder/image.jpg';

    protected $_productsIds = null;

    public function _construct()
    {
        parent::_construct();
        $this->_init('awshopbybrand/brand', 'id');
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        if (null === $this->_productsIds) {
            /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
            $collection = Mage::getModel('catalog/product')->getCollection();
            $collection->addStoreFilter();

            if (!$collection->isEnabledFlat()) {
                $collection
                    ->addAttributeToSelect(AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE)
                    ->addFieldToFilter(
                        AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE,
                        array(
                            'eq' => $this->getId()
                        )
                    );
            } else {
                $sbbTable = Mage::getSingleton('eav/config')
                    ->getAttribute('catalog_product', AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE)
                    ->getBackend()
                    ->getTable()
                ;
                $attribute = Mage::getModel('eav/entity_attribute')->loadByCode(
                    Mage_Catalog_Model_Product::ENTITY, AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE
                );

                $connection = Mage::getSingleton('core/resource')->getConnection('read');
                $conditions = array(
                    "aw_sbb_table.entity_id = e.entity_id",
                    $connection->quoteInto("aw_sbb_table.attribute_id = ?", $attribute->getId()),
                    $connection->quoteInto("aw_sbb_table.value IN (?)", $this->getId()),
                );

                $collection
                    ->getSelect()
                    ->join(
                        array('aw_sbb_table' => $sbbTable),
                        implode(' AND ', $conditions),
                        array()
                    )
                ;
            }
            $this->_productsIds = $collection->getAllIds();
        }
        return $this->_productsIds;
    }

    public function getUrl()
    {
        $allBrandsUrlKey = Mage::helper('awshopbybrand/config')->getAllBrandsUrlKey();
        return Mage::getBaseUrl() . $allBrandsUrlKey . '/' . $this->getData('url_key');
    }

    /**
     * @return array
     */
    protected function _getExistedProducts()
    {
        $ids = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect(AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE)
            ->addFieldToFilter(
                AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE,
                array(
                    'eq' => $this->getId(),
                )
            )
            ->getAllIds()
        ;
        return $ids;
    }

    /**
     * @return array
     */
    protected function _getCurrentProducts()
    {
        $ids = array();
        if (null !== $this->getData('products')) {
            $ids = explode(',', $this->getData('products'));
        }
        return array_filter($ids);
    }

    protected function _afterSave()
    {
        $_result = parent::_afterSave();

        if (!$this->getData('source') && ($this->getOrigData('products') != $this->getData('products'))) {
            $currentProducts = $this->_getCurrentProducts();
            $existedProducts = $this->_getExistedProducts();
            if ($existedProducts) {
                Mage::getSingleton('catalog/product_action')
                    ->updateAttributes(
                        $existedProducts,
                        array(
                            AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE => 0
                        ),
                        0
                    )
                ;
            }

            if ($currentProducts) {
                Mage::getSingleton('catalog/product_action')
                    ->updateAttributes(
                        $currentProducts,
                        array(
                            AW_Shopbybrand_Model_Source_Attribute::ATTRIBUTE_CODE => $this->getId()
                        ),
                        0
                    )
                ;
            }

            $indexProcess = Mage::getSingleton('index/indexer')->getProcessByCode('catalog_eav_attribute');
            if ($indexProcess) {
                $indexProcess->reindexAll();
            }
        }

        return $_result;
    }

    public function addProduct($productId)
    {
        if (null === $this->getData('products')) {
            $this->setData('products', (string)$productId);
        } else {
            $productsIds = explode(',', $this->getData('products'));
            if (!in_array($productId, $productsIds)) {
                //add product position
                Mage::getModel('awshopbybrand/brand_product')
                    ->setBrandId($this->getId())
                    ->setProductId($productId)
                    ->save()
                ;
                array_push($productsIds, $productId);
                $this->setData('products', implode(',', $productsIds));
            }
        }

        return $this;
    }

    public function removeProduct($productId)
    {
        if (null !== $this->getData('products')) {
            $productsIds = explode(',', $this->getData('products'));
            if ($key = array_search($productId, $productsIds)) {
                //remove product position
                $positionCollection = Mage::getModel('awshopbybrand/brand_product')->getCollection();
                $positionCollection
                    ->addBrandFilter($this->getId())
                    ->addProductFilter($productId)
                    ->removeAll()
                ;
                unset($productsIds[$key]);
            }
            $this->setData('products', implode(',', $productsIds));
        }
        return $this;
    }

    public function uploadImage($fileName)
    {
        $uploader = new Varien_File_Uploader($fileName);
        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'bmp'));
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $uploader->setAllowCreateFolders(true);
        // Set media as the upload dir
        $path = Mage::getBaseDir('media') . DS . 'aw_shopbybrand' . DS . $fileName . DS;

        // Upload the image
        $uploader->save($path, $uploader->getUploadedFileName());
        return $uploader->getUploadedFileName();
    }

    public function removeImage($imageName)
    {
        if (file_exists(Mage::getBaseDir('media') . DS . $imageName)) {
            unlink(Mage::getBaseDir('media') . DS . $imageName);
        }
        return $this;
    }

    public function updateProductPositions(array $selectedProducts)
    {
        //remove previous product positions
        $collection = Mage::getModel('awshopbybrand/brand_product')->getCollection();
        $collection
            ->addBrandFilter($this->getId())
            ->removeAll()
        ;

        //save product positions
        foreach ($selectedProducts as $productId => $position) {
            Mage::getModel('awshopbybrand/brand_product')
                ->setBrandId($this->getId())
                ->setProductId($productId)
                ->setPosition($position['position'])
                ->save()
            ;
        }
        return $this;
    }

    public function getProductsPositions()
    {
        if ($this->getId()) {
            $collection = Mage::getModel('awshopbybrand/brand_product')->getCollection();
            $collection->addBrandFilter($this->getId());
            return $collection->getItems();
        }
        return array();
    }
}