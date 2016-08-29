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


class AW_Shopbybrand_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction($actionName)
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/awshopbybrand')
            ->_addBreadcrumb($actionName, $this->__('Manage Brands'))
            ->_title($this->__('Shop By Brand'))
            ->_title($actionName)
        ;
        return $this;
    }

    /**
     * @return AW_Shopbybrand_Model_Brand|null
     */
    protected function _initBrandStore()
    {
    	//echo "<pre>"; print_r($this->getRequest()->getParams()); exit;
    		$brandstoremodel = Mage::getModel('awshopbybrand/brandstore')->getCollection();
    		$brandstoremodel->addFieldToFilter('brand_id',$this->getRequest()->getParam('id'));
    		$brandstoremodel->addFieldToFilter('store_id',$this->getRequest()->getParam('storeid'));
    		//echo "<pre>"; print_r($brandstoremodel->getData()); exit;
    		if(count($brandstoremodel) > 0)
    		{
    			foreach($brandstoremodel as $brandstoremodel1)
    			{
    				$store_data['store_select'] = $brandstoremodel1['store_id'];
    				$store_data['store_page_title'] = $brandstoremodel1['page_title'];
    				$store_data['store_description'] = $brandstoremodel1['description'];
    				$store_data['store_meta_keywords'] = $brandstoremodel1['meta_keywords'];
    				$store_data['store_meta_description'] = $brandstoremodel1['meta_description'];
    				$store_data['store_shipping_time'] = $brandstoremodel1['shipping_time'];
    			}	
    		}
    		else
    		{
    			$store_data['store_select'] = $this->getRequest()->getParam('storeid');
    			$store_data['store_page_title'] = "";
    			$store_data['store_description'] = "";
    			$store_data['store_meta_keywords'] = "";
    			$store_data['store_meta_description'] = "";
    			$store_data['store_shipping_time'] = "";
    		}
    		Mage::register('current_brand_store', $store_data);
    		return $store_data;        	
    }
    protected function _initBrand()
    {
    	if($this->getRequest()->getParam('storeid') != 1)
    	{
    		$this->_initBrandStore();
    	}
        $brandModel = Mage::getModel('awshopbybrand/brand');
        $brandId = (int)$this->getRequest()->getParam('id', 0);
        if ($brandId) {
            try {
                $brandModel->load($brandId);
                if (!$brandModel->getId()) {
                    throw new Exception($this->__('This brand no longer exists'));
                }
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                return null;
            }
        }

        if (null !== Mage::getSingleton('adminhtml/session')->getBrandData()) {
            $brandModel->addData(Mage::getSingleton('adminhtml/session')->getBrandData());
            Mage::getSingleton('adminhtml/session')->setBrandData(null);
        }
        Mage::register('current_brand', $brandModel);
        return $brandModel;
    }

    public function indexAction()
    {
        $this
            ->_initAction($this->__('Manage Brands'))
            ->renderLayout()
        ;
    }

    /**
     * Export customer grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'brands.csv';
        $content = $this->getLayout()
            ->createBlock('awshopbybrand/adminhtml_brand_grid')
            ->getCsvFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export customer grid to XML format
     */
    public function exportXmlAction()
    {
        $fileName = 'brands.xml';
        $content = $this->getLayout()
            ->createBlock('awshopbybrand/adminhtml_brand_grid')
            ->getExcelFile()
        ;
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function editAction()
    {
        $brandModel = $this->_initBrand();
        if (null === $brandModel) {
            return $this->_redirect('*/*/');
        }
        $isNewBrandPage = !!$this->getRequest()->getParam('id', false);
        $this
            ->_initAction($this->__($isNewBrandPage ? 'Edit Brand' : 'New Brand'))
            ->renderLayout()
        ;
        return $this;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function massDeleteAction()
    {
        $brandIdList = $this->getRequest()->getParam('id');
        if (!is_array($brandIdList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
            return $this->_redirect('*/*/');
        }
        try {
            foreach ($brandIdList as $brandId) {
                Mage::getModel('awshopbybrand/brand')->setId($brandId)->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Total of %d record(s) were successfully deleted', count($brandIdList))
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
        return $this;
    }

    public function massStatusAction()
    {
        $brandIdList = $this->getRequest()->getParam('id');
        if (!is_array($brandIdList)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
            return $this->_redirect('*/*/');
        }
        try {
            foreach ($brandIdList as $brandId) {
                $model = Mage::getModel('awshopbybrand/brand')->load($brandId);
                $model->setData('brand_status', $this->getRequest()->getParam('brand_status'));
                $model->save();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__('Total of %d record(s) were successfully updated', count($brandIdList))
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
        return $this;
    }

    public function deleteAction()
    {
        $brandModel = $this->_initBrand();
        if (null === $brandModel) {
            return $this->_redirect('*/*/');
        }
        try {
            $brandModel->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Brand was successfully deleted'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        return $this->_redirect('*/*/');
    }

    public function saveAction()
    {
        if (!$data = $this->getRequest()->getPost()) {
            $this->_redirect('*/*/');
        }
        $data = $this->getRequest()->getPost();
        //$data['store_select'];
       // echo "<pre>"; print_r($data); exit;
        $brandid = $this->getRequest()->getParam('id');
        
       
	
        
        $brandcategoryimage = Mage::getModel('awshopbybrand/categoryimage')->getCollection()->addFieldToFilter('brand_id',array('eq' => $brandid));
        $catdataimage = array();
        foreach($brandcategoryimage as $brandcategoryimage1)
        {
        	//echo "<pre>"; print_r($brandcategoryimage1->getData());
        	$catdataimage[$brandcategoryimage1['name']] = $brandcategoryimage1['id'];
        }

        foreach($_FILES['categoryimage']['name'] as $categoryimagekey => $categoryimagevalue)
        {
        	//echo "<br />key : ".$categoryimagekey." value : ".$categoryimagevalue; continue;
        	foreach($categoryimagevalue as $categoryimagevaluekey => $categoryimagevaluevalue)
        	{
        		$catimgbrandarr = array();
        		if($data['categoryimage'][$categoryimagekey][$categoryimagevaluekey]['delete']==1)
        		{
        			//echo $catdataimage[$categoryimagevaluekey]; exit;	
        			$catimgbrandarr['image'] = "";
        			Mage::getModel('awshopbybrand/categoryimage')->load($catdataimage[$categoryimagevaluekey])->addData($catimgbrandarr)->save();
        		}
        		else 
        		{
        			if(isset($_FILES['categoryimage']['name'][$categoryimagekey][$categoryimagevaluekey]))
        			{
        				$target_dir1 = Mage::getBaseDir('media') . DS . "aw_shopbybrand/categoryimage/".$categoryimagekey."/";
        				mkdir(Mage::getBaseDir('media') . DS . "aw_shopbybrand/categoryimage/".$categoryimagekey, 0777);
        				$target_file1 = $target_dir1 . $_FILES['categoryimage']['name'][$categoryimagekey][$categoryimagevaluekey];
        				if (move_uploaded_file($_FILES['categoryimage']['tmp_name'][$categoryimagekey][$categoryimagevaluekey], $target_file1)) {
        					//echo "<br /><b>".$_FILES['categoryimage']['name'][$categoryimagekey][$categoryimagevaluekey]."</b><br />";
        					if($catdataimage[$categoryimagevaluekey])
        					{
        						$catimgbrandarr['image'] = $categoryimagevaluevalue;
        						Mage::getModel('awshopbybrand/categoryimage')->load($catdataimage[$categoryimagevaluekey])->addData($catimgbrandarr)->save();
        					}
        					else
        					{
        						$catimgbrandarr['brand_id'] = $categoryimagekey;
        						$catimgbrandarr['name'] = $categoryimagevaluekey;
        						$catimgbrandarr['image'] = $categoryimagevaluevalue;
        						Mage::getModel('awshopbybrand/categoryimage')->setData($catimgbrandarr)->save();
        					}
        				} else {
        				}
        			}	
        		}
        	}
        }

        
        $links = $this->getRequest()->getPost('links', array());

        if (array_key_exists('products', $links)) {
            $selectedProducts = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['products']);
            $data['products'] = implode(',', array_keys($selectedProducts));
        }

        $brandModel = $this->_initBrand();
        if (null === $brandModel) {
            return $this->_redirect('*/*/');
        }

        if (isset($data['banner']['delete'])) {
        	$brandModel->removeImage($data['banner']['value']);
        	$brandModel->setBanner('');
        }
        
        if (isset($data['image']['delete'])) {
            $brandModel->removeImage($data['image']['value']);
            $brandModel->setImage('');
        }

        if (isset($data['icon']['delete'])) {
            $brandModel->removeImage($data['icon']['value']);
            $brandModel->setIcon('');
        }
        
        if (isset($data['store_ids'])) {
	    $data['store_ids'] =  implode(',', $data['store_ids']);
	}
        
        

        try {
        	if ($_FILES['banner']['tmp_name']) {
        		$iconFileName = $brandModel->uploadImage('banner');
        		$data['banner'] = 'aw_shopbybrand' . DS . 'banner' . DS . $iconFileName;
        	} else {
        		unset($data['banner']);
        	}
        	
            if ($_FILES['image']['tmp_name']) {
                $imageFileName = $brandModel->uploadImage('image');
                $data['image'] = 'aw_shopbybrand' . DS . 'image' . DS . $imageFileName;
            } else {
                unset($data['image']);
            }

            if ($_FILES['icon']['tmp_name']) {
                $iconFileName = $brandModel->uploadImage('icon');
                $data['icon'] = 'aw_shopbybrand' . DS . 'icon' . DS . $iconFileName;
            } else {
                unset($data['icon']);
            }

            
            
            $brandModel
                ->addData($data)
                ->save()
            ;
            if(($data['store_select']) && ($data['store_select'] != 1))
            {
            	$brandstoremodel = Mage::getModel('awshopbybrand/brandstore');
            	$brandstore = $brandstoremodel->getCollection();
            	$brandstore->addFieldToFilter('brand_id',$brandid);
            	$brandstore->addFieldToFilter('store_id',$data['store_select']);
            	if(count($brandstore) > 0)
            	{
            		foreach($brandstore as $brandstore1)
            		{
            			$branddata = $brandstoremodel->load($brandstore1['id']);
            			$branddata->setDescription($data['store_description']);
            			$branddata->setPageTitle($data['store_page_title']);
            			$branddata->setMetaKeywords($data['store_meta_keywords']);
            			$branddata->setMetaDescription($data['store_meta_description']);
            			$branddata->setShippingTime($data['store_shipping_time']);
            			$branddata->save();
            		}
            	}
            	else
            	{
            		$branddata = $brandstoremodel->load();
            		$branddata->setBrandId($brandid);
            		$branddata->setStoreId($data['store_select']);
            		$branddata->setDescription($data['store_description']);
            		$branddata->setPageTitle($data['store_page_title']);
            		$branddata->setMetaKeywords($data['store_meta_keywords']);
            		$branddata->setMetaDescription($data['store_meta_description']);
            		$branddata->setShippingTime($data['store_shipping_time']);
            		$branddata->save();
            	}
            }
           
            if (isset($selectedProducts)) {
                $brandModel->updateProductPositions($selectedProducts);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Brand was successfully saved'));

            Mage::getSingleton('adminhtml/session')->setBrandData(null);
            
            if ($this->getRequest()->getParam('back')) {
                ///return $this->_redirect(
                  //  '*/*/edit',
                   // array(
                     //   'id'         => $brandModel->getId(),
                       // 'active_tab' => Mage::app()->getRequest()->getParam('tab'),
                    //)
                //);
            	$this->_redirectReferer();
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setBrandData($data);
           // return $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            $this->_redirectReferer();
        }
        $this->_redirectReferer();
        //return $this->_redirect('*/*/');
    }

    /**
     * Get related products grid
     */
    public function productsgridAction()
    {
        if (null === $this->_initBrand()) {
            return $this->_redirect('*/*/');
        }
        $this
            ->loadLayout()
            ->renderLayout()
        ;
        return $this;
    }

    public function productsAction()
    {
        if (null === $this->_initBrand()) {
            return $this->_redirect('*/*/');
        }
        $this
            ->loadLayout()
            ->renderLayout()
        ;
        return $this;
    }
}