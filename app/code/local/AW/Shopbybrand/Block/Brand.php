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


class AW_Shopbybrand_Block_Brand extends Mage_Core_Block_Template
{
    /**
     * @return AW_Shopbybrand_Model_Brand
     */
    public function getBrand()
    {
        return Mage::registry('current_brand');
    }

    public function getDescription()
    {
        $description = $this->getBrand()->getDescription();
        return Mage::helper('cms')->getBlockTemplateProcessor()->filter($description);
    }

    public function getImageUrl($brand, $width = null, $height = null)
    {
        if ($brand->getImage()) {
            $imageUrl = $this->helper('awshopbybrand')->resizeImg($brand->getImage(), $width, $height);
        } else {
            $imageUrl = Mage::getDesign()->getSkinBaseUrl() . AW_Shopbybrand_Model_Brand::DEFAULT_IMAGE_PATH;
        }
        return $imageUrl;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection()
    {
        $products = $this->getBrand()->getProducts();

        /** @var $productCollection Mage_Catalog_Model_Resource_Product_Collection */
        $productCollection = Mage::getSingleton('awshopbybrand/layer')->getProductCollection();
        $productCollection
            ->addAttributeToFilter('entity_id', array('in' => $products))
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
        }
        return $productCollection;
    }

    protected function _prepareLayout()
    {
        if (!$this->getBrand()) {
            return $this;
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
        	
        	$storeId = Mage::app()->getStore()->getStoreId();
        	$brandstoremodel = Mage::getModel('awshopbybrand/brandstore')->getCollection();
        	$brandstoremodel->addFieldToFilter('brand_id',$this->getBrand()->getData('id'));
        	$brandstoremodel->addFieldToFilter('store_id',$storeId);
        	if(count($brandstoremodel) > 0)
        	{
        		foreach($brandstoremodel as $brandstoremodel1)
        		{
        			$store_data['store_page_title'] = $brandstoremodel1['page_title'];
        			$store_data['store_meta_keywords'] = $brandstoremodel1['meta_keywords'];
        			$store_data['store_meta_description'] = $brandstoremodel1['meta_description'];
        		}
        	}
        	else
        	{
        		$store_data['store_page_title'] = "";
        		$store_data['store_meta_keywords'] = "";
        		$store_data['store_meta_description'] = "";
        	}
        	
        	if($store_data['store_page_title'])
        	{
        		if ($pageTitle = $this->escapeHtml($store_data['store_page_title'])) {
        			$headBlock->setTitle($pageTitle);
        		} elseif ($title = $this->escapeHtml($this->getBrand()->getData('title'))) {
        			$headBlock->setTitle($title);
        		}
        	}
        	else 
        	{
        		if ($pageTitle = $this->escapeHtml($this->getBrand()->getData('page_title'))) {
        			$headBlock->setTitle($pageTitle);
        		} elseif ($title = $this->escapeHtml($this->getBrand()->getData('title'))) {
        			$headBlock->setTitle($title);
        		}
        	}

        	if($store_data['store_meta_keywords'])
        	{
        		if ($metaKey = $this->escapeHtml($store_data['store_meta_keywords'])) {
        			$headBlock->setKeywords($metaKey);
        		}
        	}
        	else 
        	{
        		if ($metaKey = $this->escapeHtml($this->getBrand()->getData('meta_keywords'))) {
        			$headBlock->setKeywords($metaKey);
        		}	
        	}
        	
        	if($store_data['store_meta_description'])
        	{
        		if ($metaDesc = $this->escapeHtml($store_data['store_meta_description'])) {
        			$headBlock->setDescription($metaDesc);
        		}
        	}
        	else 
        	{
        		if ($metaDesc = $this->escapeHtml($this->getBrand()->getData('meta_description'))) {
        			$headBlock->setDescription($metaDesc);
        		}	
        	}
        }
        return parent::_prepareLayout();
    }

    protected function _beforeToHtml()
    {
        $productListBlock = $this->getLayout()->getBlock('productlist');
        if ($productListBlock) {
            $productListBlock->setCollection($this->getProductCollection());
        }
        return parent::_beforeToHtml();
    }
}