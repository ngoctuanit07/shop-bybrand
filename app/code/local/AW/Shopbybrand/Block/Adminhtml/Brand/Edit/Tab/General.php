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


class AW_Shopbybrand_Block_Adminhtml_Brand_Edit_Tab_General
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return $this->__('General');
    }

    public function getTabTitle()
    {
        return $this->__('General');
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

        $_fieldset = $form->addFieldset(
            'brand_form',
            array(
                'legend' => $this->__('General'),
            )
        );

        $_data = Mage::registry('current_brand');

        $_fieldset->addField(
            'title',
            'text',
            array(
                'name'     => 'title',
                'label'    => $this->__('Title'),
                'title'    => $this->__('Title'),
                'required' => true,
            )
        );
        
        if (!Mage::app()->isSingleStoreMode()) {
            $_fieldset->addField('store_ids', 'multiselect',
                array(
                    'name'     => 'store_ids',
                    'label'    => $this->__('Store View'),
                    'required' => TRUE,
                    'values'   => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(FALSE, TRUE),
                )
            );
        } else {
            $_fieldset->addField('store_ids', 'hidden',
                array(
                   'name'      => 'store_ids',
                   'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            $_data->setStoreIds(Mage::app()->getStore(true)->getId());
        }

        $_fieldset->addField(
            'brand_status',
            'select',
            array(
                'name'   => 'brand_status',
                'label'  => $this->__('Status'),
                'title'  => $this->__('Status'),
                'values' => Mage::getModel('awshopbybrand/source_status')->toOptionArray(),
            )
        );

        $_fieldset->addField(
            'is_show_in_sidebar',
            'select',
            array(
                'name'   => 'is_show_in_sidebar',
                'label'  => $this->__('Show in Sidebar'),
                'title'  => $this->__('Show in Sidebar'),
                'values' => Mage::getModel('awshopbybrand/source_yesno')->toOptionArray(),
            )
        );

        $_fieldset->addField(
            'priority',
            'text',
            array(
                'name'  => 'priority',
                'label' => $this->__('Sort Order'),
                'title' => $this->__('Sort Order'),
            )
        );
        
        
        $attributeSetCollection = Mage::getResourceModel('eav/entity_attribute_set_collection') ->load();
        $_menuItems[] = array(
						'value'     =>'',
						'label'     => '',
				);
        foreach ($attributeSetCollection as $id=>$attributeSet) 
        {
		  $entityTypeId = $attributeSet->getEntityTypeId();
		  $name = $attributeSet->getAttributeSetName();
		  if($name=='Default') continue;
		  $_menuItems[] = array(
						'value'     => $name,
						'label'     => $name,
				);
		}
		
		
		$_fieldset->addField(
            'industry',
            'select',
            array(
                'name'  => 'industry',
                'label' => $this->__('Industry'),
                'title' => $this->__('Industry'),
                'values'    => $_menuItems,
            )
        ); 
        
        
        $attribute = Mage::getSingleton('eav/config')->getAttribute('catalog_product', 'country_of_manufacture'); 
		if ($attribute->usesSource()) 
		{
			$options = $attribute->getSource()->getAllOptions(false);
			foreach($options as $option) {
				
				$_menuItems2[] = array(
						'value'     => $option['value'],
						'label'     => $option['label'],
				);
				
			}
		}
		
		
        $_fieldset->addField(
            'country',
            'select',
            array(
                'name'  => 'country',
                'label' => $this->__('Country'),
                'title' => $this->__('country'),
                'values'    => $_menuItems2,
            )
        ); 

        $form->setValues($_data->getData());
        return parent::_prepareForm();
    }
}
