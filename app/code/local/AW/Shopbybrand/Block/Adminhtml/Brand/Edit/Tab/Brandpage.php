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


class AW_Shopbybrand_Block_Adminhtml_Brand_Edit_Tab_Brandpage
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return $this->__('Brand Page');
    }

    public function getTabTitle()
    {
        return $this->__('Brand Page');
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

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->setForm($form);

        $_fieldset = $form->addFieldset(
            'brandpage_form',
            array(
                'legend' => $this->__('Brand Page')
            )
        );

        $_data = Mage::registry('current_brand');
        if(Mage::registry('current_brand_store'))
        {
        	$_data_store = Mage::registry('current_brand_store');
        }
        else
        {
        	$_data_store['store_select'] = 1;
        }

        $allStores = Mage::app()->getStores();
        $_storeCodearr = array();
        $_storeCodearr[] = 'Please Select';
        foreach ($allStores as $_eachStoreId => $val)
        {
        	$_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
        	$_storeCodearr[Mage::app()->getStore($_eachStoreId)->getId()] = Mage::app()->getStore($_eachStoreId)->getCode();
        }
        
        $brandid = $this->getRequest()->getParam('id');
        $geturl = Mage::getUrl();
        $_fieldset->addField('store_select', 'select', array(
        		'name' => 'store_select',
        		'label' => $this->__('Store View'),
        		'title' => $this->__('Store View'),
        		'onchange' => 'changestorebrand(this.value)',
        		//'onchange' => 'SubmitRequestBrand()',
        		'values' => Mage::getSingleton('adminhtml/system_store')
        		->getStoreValuesForForm(false, true),
        		'after_element_html' => "<script>
        			function changestorebrand(storeid){
        				///alert('".Mage::getUrl('awshopbybrand_admin/adminhtml_brand/edit')."/id/".$brandid."/storeid/'+storeid);
						window.location.href = '".Mage::getUrl('awshopbybrand_admin/adminhtml_brand/edit')."id/".$brandid."/storeid/'+storeid+'/active_tab/brand_info_tabs_brandpage';
    				}
        		</script>"
        ));

        if(($this->getRequest()->getParam('storeid')) && ($this->getRequest()->getParam('storeid') != 1))
        {
        	$_fieldset->addField(
        			'store_page_title',
        			'text',
        			array(
        					'name'     => 'store_page_title',
        					'label'    => $this->__('Page Title'),
        					'title'    => $this->__('Page Title'),
        			)
        	);
        }
        else
        {
        	$_fieldset->addField(
        			'page_title',
        			'text',
        			array(
        					'name'     => 'page_title',
        					'label'    => $this->__('Page Title'),
        					'title'    => $this->__('Page Title'),
        			)
        	);
        }


        $_fieldset->addField(
            'url_key',
            'text',
            array(
                'name'     => 'url_key',
                'label'    => $this->__('URL key'),
                'title'    => $this->__('URL key'),
                'required' => true,
                'class'    => 'validate-identifier',
            )
        );
        $inputType = 'textarea';
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $inputType = 'editor';
        }

        $wysiwygConfig = Mage::getSingleton('awshopbybrand/source_wysiwyg')->getConfig(
            array('add_variables' => false)
        );


        
        if(($this->getRequest()->getParam('storeid')) && ($this->getRequest()->getParam('storeid') != 1))
        {
        	$_fieldset->addField(
        			'store_description',
        			$inputType,
        			array(
        					'name'    => 'store_description',
        					'label'   => $this->__('Brand description'),
        					'title'   => $this->__('Brand description'),
        					'config'  => $wysiwygConfig,
        					'wysiwyg' => true,
        					'container_id' => 'row-id',
        					'class' => 'store_description',
        					'value' => $store_description
        			)
        	);
        }
        else
        {
        	$_fieldset->addField(
        			'description',
        			$inputType,
        			array(
        					'name'    => 'description',
        					'label'   => $this->__('Brand description'),
        					'title'   => $this->__('Brand description'),
        					'config'  => $wysiwygConfig,
        					'wysiwyg' => true,
        			)
        	);
        }

        if(($this->getRequest()->getParam('storeid')) && ($this->getRequest()->getParam('storeid') != 1))
        {
        	$_fieldset->addField(
        			'store_meta_keywords',
        			'textarea',
        			array(
        					'name'  => 'store_meta_keywords',
        					'label' => $this->__('Meta keywords'),
        					'title' => $this->__('Meta keywords'),
        			)
        	);
        }
        else
        {
        	$_fieldset->addField(
        			'meta_keywords',
        			'textarea',
        			array(
        					'name'  => 'meta_keywords',
        					'label' => $this->__('Meta keywords'),
        					'title' => $this->__('Meta keywords'),
        			)
        	);
        }

        if(($this->getRequest()->getParam('storeid')) && ($this->getRequest()->getParam('storeid') != 1))
        {
        	$_fieldset->addField(
        			'store_meta_description',
        			'textarea',
        			array(
        					'name'  => 'store_meta_description',
        					'label' => $this->__('Meta description'),
        					'title' => $this->__('Meta description'),
        			)
        	);
        }
        else
        {
        	$_fieldset->addField(
        			'meta_description',
        			'textarea',
        			array(
        					'name'  => 'meta_description',
        					'label' => $this->__('Meta description'),
        					'title' => $this->__('Meta description'),
        			)
        	);
        }
        
        if(($this->getRequest()->getParam('storeid')) && ($this->getRequest()->getParam('storeid') != 1))
        {
        	$_fieldset->addField(
        			'store_shipping_time',
        			'text',
        			array(
        					'name'     => 'store_shipping_time',
        					'label'    => $this->__('Shipping Time'),
        					'title'    => $this->__('Shipping Time'),
        			)
        	);
        }
        else
        {
        	$_fieldset->addField(
        			'shipping_time',
        			'text',
        			array(
        					'name'     => 'shipping_time',
        					'label'    => $this->__('Shipping Time'),
        					'title'    => $this->__('Shipping Time'),
        			)
        	);
        }

        if($_data_store)
        {
        	$datamerager = array_merge($_data->getData(),$_data_store);
        }
        else {
        	$datamerager = $_data->getData();
        }
		//echo "<pre>"; print_r($_data->getData()); print_r($datamerager); echo "</pre>";
        $form->setValues($datamerager);
        //$form->setValues($_data1);
        return parent::_prepareForm();
    }
}
?>