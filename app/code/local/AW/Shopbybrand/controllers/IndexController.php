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


class AW_Shopbybrand_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function allBrandsViewAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function brandPageViewAction()
    {
        $brandId = $this->getRequest()->getParam('brand_id', null);
        if (null === $brandId) {
            $this->_redirect('cms/noRoute');
        }

        $brandModel = Mage::getModel('awshopbybrand/brand')->load($brandId);
        if (null === $brandModel->getId()) {
            $this->_redirect('cms/noRoute');
        }

		$storeId = Mage::app()->getStore()->getStoreId();
		$availableStores = explode(",",$brandModel->getData('store_ids'));
		//echo "<pre>"; print_r($availableStores); exit;
		
		if (!in_array($storeId, $availableStores)) {
			$this->_redirect('brands');
            return;
		}
		
        Mage::register('current_brand', $brandModel);

        $this->loadLayout();
        $this->getRequest()
            ->setActionName($brandModel->getUrlKey())
            ->setControllerName('')
            ->setRouteName(Mage::helper('awshopbybrand/config')->getAllBrandsUrlKey())
        ;

        $this->renderLayout();
    }
    
    public function __getCountryAbbr($country)
    {
		$code = array(
		"United States"  => "US",		 
		);
		if($code[$country]){return $code[$country];}else{return $country;}
		 
	}
    
     
    public function searchAction()
    {
		//echo "AAA";
	$industry = $this->getRequest()->getParam('industry');	
	$country = $this->getRequest()->getParam('country');	
	//echo $industry;
	$brandsCollection = Mage::getModel('awshopbybrand/brand')->getCollection();
	$brandsCollection
			->addFieldToFilter('store_ids', array(
					array('finset' => '0'),
					array('finset' => (string) (Mage::app()->getStore()->getId())),
				))
			->addStatusFilter()
			->setOrder('priority', Varien_Data_Collection::SORT_ORDER_ASC);
	if($industry) { $brandsCollection->addFieldToFilter('industry', $industry );}
	if($country) 
	{ 
		if(trim($country)!='Oups, I have no idea, are you on water?') 
		{ 
			//$brandsCollection->addFieldToFilter('country', $this->__getCountryAbbr($country)); 
			$brandsCollection->addFieldToFilter('country', $country); 
		}	
	} 	
	
	//echo $brandsCollection->getSelect();
							
    $html='';
    $block=$this->getLayout()->createBlock('awshopbybrand/allbrands'); 
    
    $html=$html .'<div id="brandres">';     
	foreach ($brandsCollection as $brand)
	{
		
		$html=$html .'<div class="aw_brand_container new">';
		$html=$html .'<a href="'.$block->getBrandUrl($brand).'" class="aw_brands_url" title="'.$block->escapeHtml($brand->getTitle()).'">';
		$html=$html .'<div class="aw_brand_logo">';
		$html=$html .'<img src="'.$block->getImageUrl($brand, 150, 150).'"  width="150" />';
		$html=$html ."</div>";
		$html=$html . '<div class="aw_brand_title">'.$block->escapeHtml($brand->getTitle()).'</div>';
		$html=$html ."</a>";
		$html=$html ." </div>" ;   
		        
                   
    }
	if($brandsCollection->count()==0)
	{
	    
	   $html=$html ."<p class='nobrand'>No Result Found</p>" ;   
	    
    }
    $html=$html ." </div>" ;           	 
	 echo $html;
             
                
   }                 
                     
  
 
	 
}
