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


class AW_Shopbybrand_Block_Adminhtml_Brand_Edit_Tab_Categoryimages
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return $this->__('Category Images');
    }

    public function getTabTitle()
    {
        return $this->__('Category Images');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $_fieldset = $form->addFieldset('brand_category_images_form', array('legend' => $this->__('Category Images')));

        //echo $brandkey[sizeof($brandkey)-1];
       // echo $this->getRequest()->getParam('id'); exit;
       $brandid = $this->getRequest()->getParam('id');
       $brandcategoryimage = Mage::getModel('awshopbybrand/categoryimage')->getCollection()->addFieldToFilter('brand_id',array('eq' => $brandid));
       $catdataimage = array();
       foreach($brandcategoryimage as $brandcategoryimage1)
       {
       //echo "<pre>"; print_r($brandcategoryimage1->getData());
       		$catdataimage[$brandcategoryimage1['name']] = $brandcategoryimage1['image'];
       }
       
        $brandsCollection = Mage::getModel('awshopbybrand/brand')->getCollection()->addFieldToFilter('id',array('eq'=>$this->getRequest()->getParam('id')));

       foreach ($brandsCollection as $item)
       {
        	$productids = $item['products']; 
        	$brandtitle = $item['title'];
        	$brandstoreids = $item['store_ids'];
       }
      
       $brandstoreidsarr = explode(",",$brandstoreids);
       //echo "<pre>"; print_r($brandstoreidsarr); echo "</pre>";
       //echo "<pre>"; print_r($productids); echo "</pre>";
        $colorOfProduct = $brandtitle;
        $attributeId = Mage::getResourceModel('eav/entity_attribute')->getIdByCode('catalog_product','aw_shopbybrand_brand');
        $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
        $attributeOptions = $attribute ->getSource()->getAllOptions();
       
        foreach ($attributeOptions as $option) {
        	if ($option['label'] == $colorOfProduct)
        		$optionvalue = $option['value'];
        }
        $proexplode = explode(",",$productids);
        $prodisdsrr = array();
        foreach($proexplode as $proexplode1)
        {
        	if($proexplode1=="") continue;
        	$prodisdsrr[] = $proexplode1;
        }
        $productids = implode(",",$prodisdsrr);
       if(trim($productids) != "")
       {
	       	$read = Mage::getSingleton( 'core/resource' )->getConnection( 'core_read' );
	       	$salesforce_components = Mage::getSingleton( 'core/resource' )->getTableName( 'catalog_category_product' );
	       	$query = "select * from ".$salesforce_components." where product_id in(".$productids.")";
	       	$result = $read->query( $query );
	       	$categoryid = array();
	       	while ( $row = $result->fetch() )
	       	{
	       		if(!in_array($row['category_id'], $categoryid))
	       		{
	       			$categoryid[] = $row['category_id'];
	       		}
	       	}
	       	$allStores = Mage::app()->getStores();
	       	$storeids = array();
	       	foreach ($allStores as $_eachStoreId => $val)
	       	{
	       		if(Mage::app()->getStore($_eachStoreId)->getIsActive()==1)
	       		{
	       			$storeids[] = Mage::app()->getStore($_eachStoreId)->getId();
	       			/*echo " <br />Name : ".Mage::app()->getStore($_eachStoreId)->getName(); //Store Name
	       			 echo " Id : ".Mage::app()->getStore($_eachStoreId)->getId(); // Store Id
	       			 echo " Code : ".Mage::app()->getStore($_eachStoreId)->getCode(); // Store Code
	       			echo " Status : ".Mage::app()->getStore($_eachStoreId)->getIsActive(); // Store Code*/
	       		}
	       	
	       	}
	       	
	       	
	       	$rootchildcategoryid = array();
	       	$rootcatname = array();
	       	foreach($storeids as $storeids1)
	       	{
	       		//if($storeids1!=15) continue;
	       		if(!in_array($storeids1,$brandstoreidsarr)) continue;
	       		$rootId = Mage::app()->getStore($storeids1)->getRootCategoryId();
	       		//echo "<br />root id : ".$rootId."  name : ".Mage::getModel('catalog/category')->load($rootId)->getName();
	       		$rootcatname[$rootId] = Mage::getModel('catalog/category')->load($rootId)->getName();
	       		$rootchildcategory = Mage::getModel('catalog/category')->load($rootId)->getChildrenCategories();
	       		foreach($rootchildcategory as $rootchildcategory1)
	       		{
	       			$rootchildcategoryid[] = $rootchildcategory1->getId();
	       		}
	       	}
	       	/*echo "<pre>"; print_r($rootcatname); echo "</pre>";*/
	       	$catposition = array();
	       	$allcatids = array();
	       	
	       	foreach($categoryid as $categoryid1 )
	       	{
	       		foreach($storeids as $storeids1)
	       		{
	       			$catdata = Mage::getModel('catalog/category')->setStoreId($storeids1)->load($categoryid1);
	       			//echo "<pre>"; print_r($catdata->getData()); echo "</pre>";
	       			$explodecat = explode("/",$catdata->getpath());
	       			//if($explodecat[1]!=549) continue;
	       			//if(!in_array($explodecat[1],$brandstoreidsarr)) continue;
	       			if(!in_array($catdata->getName(),$allcatids))
	       			{
	       				if($catdata->getLevel()==2) continue;
	       				if($rootcatname[$explodecat[1]] == "") continue;
	       				$allcatids[] = $catdata->getName();
	       				//echo "<pre>"; print_r($catdata->getData()); echo "</pre>";
	       				//echo "<br />".$rootcatname[$explodecat[1]]." : ".$catdata->getName()."(".$catdata->getId().")";
	       				$imageNote = $this->__(
	       						'Optimal image size is %s x %s px.',
	       						AW_Shopbybrand_Model_Brand::IMAGE_HEIGHT,
	       						AW_Shopbybrand_Model_Brand::IMAGE_WIDTH
	       				);
	       				if($catdataimage[$catdata->getName()])
	       				{
	       					$target_dir = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "aw_shopbybrand/categoryimage/".$brandid."/".$catdataimage[$catdata->getName()];
	       					$_fieldset->addField(
	       							'categoryimage['.$brandid.']['.$catdata->getName().']', 'image', array(
	       									'label'    => $this->__($catdata->getName()),
	       									'required' => false,
	       									'name'     => 'categoryimage['.$brandid.']['.$catdata->getName().']',
	       									'note'     => $imageNote,
	       									'value' => $target_dir
	       							)
	       					);
	       				}
	       				else {
	       					$_fieldset->addField(
	       							'categoryimage['.$brandid.']['.$catdata->getName().']', 'image', array(
	       									'label'    => $this->__($catdata->getName()),
	       									'required' => false,
	       									'name'     => 'categoryimage['.$brandid.']['.$catdata->getName().']',
	       									'note'     => $imageNote
	       							)
	       					);
	       				}
	       				 
	       			}	
	       		}	       		
	       	}
       }
           //	echo "<pre>";
           	

           	//echo "<pre>"; print_r($categoryid); echo "</pre>";
			
        	return parent::_prepareForm();
    }
}