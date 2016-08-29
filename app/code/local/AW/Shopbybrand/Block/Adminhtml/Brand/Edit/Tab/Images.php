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


class AW_Shopbybrand_Block_Adminhtml_Brand_Edit_Tab_Images
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function getTabLabel()
    {
        return $this->__('Images');
    }

    public function getTabTitle()
    {
        return $this->__('Images');
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
        $_fieldset = $form->addFieldset('brand_form', array('legend' => $this->__('Images')));

        $brandModel = Mage::registry('current_brand');
        
        $iconNote = $this->__(
        		'Optimal icon size is %s x %s px.',
        		AW_Shopbybrand_Model_Brand::ICON_HEIGHT,
        		AW_Shopbybrand_Model_Brand::ICON_WIDTH
        );
        $_fieldset->addField(
        		'banner',
        		'image',
        		array(
        				'label'    => $this->__('Upload banner'),
        				'required' => false,
        				'name'     => 'banner',
        				'note'     => $iconNote,
        		)
        );

        $iconNote = $this->__(
            'Optimal icon size is %s x %s px.',
            AW_Shopbybrand_Model_Brand::ICON_HEIGHT,
            AW_Shopbybrand_Model_Brand::ICON_WIDTH
        );
        $_fieldset->addField(
            'icon',
            'image',
            array(
                'label'    => $this->__('Upload icon'),
                'required' => false,
                'name'     => 'icon',
                'note'     => $iconNote,
            )
        );

        $imageNote = $this->__(
            'Optimal image size is %s x %s px.',
            AW_Shopbybrand_Model_Brand::IMAGE_HEIGHT,
            AW_Shopbybrand_Model_Brand::IMAGE_WIDTH
        );
        $_fieldset->addField(
            'image', 'image', array(
                'label'    => $this->__('Upload image'),
                'required' => false,
                'name'     => 'image',
                'note'     => $imageNote,
            )
        );

        $form->setValues($brandModel->getData());
        return parent::_prepareForm();
    }
}