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


class AW_Shopbybrand_Model_Observer
{
    protected function forwardToAllBrands($request)
    {
        $request->initForward()
            ->setControllerName('index')
            ->setModuleName('shopbybrands')
            ->setActionName('allBrandsView')
            ->setDispatched(false)
        ;
        return $this;
    }

    public function afterProductSave(Varien_Event_Observer $observer)
    {
        $product = $observer->getEvent()->getDataObject();

        $newBrandId = $product->getData('aw_shopbybrand_brand');
        $oldBrandId = $product->getOrigData('aw_shopbybrand_brand');
        $productId = $product->getId();

        if (!$productId || $newBrandId == $oldBrandId) {
            return $this;
        }

        if ($oldBrandId) {
            $oldBrand = Mage::getModel('awshopbybrand/brand')->load($oldBrandId);
            $oldBrand->removeProduct($productId);
            $oldBrand->save();
        }

        if ($newBrandId) {
            $newBrand = Mage::getModel('awshopbybrand/brand')->load($newBrandId);
            $newBrand->addProduct($productId);
            $newBrand->save();
        }

        return $this;
    }


    public function preDispatch($event)
    {
        if (!Mage::helper('awshopbybrand')->isModuleEnabled()) {
            return $this;
        }

        $pathRoutes = explode('/', Mage::app()->getFrontController()->getRequest()->getPathInfo());
        $controllerAction = $event->getControllerAction();
        $request = $controllerAction->getRequest();
        $key = array_search(Mage::helper('awshopbybrand/config')->getAllBrandsUrlKey(), $pathRoutes);

        if ($key == 1) {
            if (!isset($pathRoutes[++$key])) {
                $this->forwardToAllBrands($request);
            } else {

                //Load brand and add it to registry
                $brandUrlKey = $pathRoutes[$key];
                if (!$brandUrlKey) {
                    return $this->forwardToAllBrands($request);
                }

                /** @var $brandsCollection AW_Shopbybrand_Model_Resource_Brand_Collection */
                $brandsCollection = Mage::getModel('awshopbybrand/brand')->getCollection();
                $brandsCollection
                    ->addFieldToFilter('url_key', array('eq' => $brandUrlKey))
                    ->addStatusFilter()
                ;

                $brand = $brandsCollection->getFirstItem();

                if (null === $brand->getId()) {
                    return $this;
                }

                $request->initForward()
                    ->setControllerName('index')
                    ->setModuleName('shopbybrands')
                    ->setActionName('brandPageView')
                    ->setPost('brand_id', $brand->getId())
                    ->setDispatched(false)
                ;
            }
        }
        return $this;
    }
}