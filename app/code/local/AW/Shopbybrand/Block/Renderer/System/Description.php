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


class AW_Shopbybrand_Block_Renderer_System_Description extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getHtmlId()
    {
        return 'al_brands_page_description';
    }

    protected function _getPathToSetting()
    {
        return 'groups[all_brands_page_setup][fields][al_brands_page_description][value]';
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $conf = '';
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $conf = Mage::getSingleton('awshopbybrand/source_wysiwyg')->getConfig(array('add_variables' => false));
        }
        $config = array(
            'name'     => $this->_getPathToSetting(),
            'html_id'  => $this->_getHtmlId(),
            'label'    => 'Content',
            'title'    => 'Content',
            'style'    => 'height:36em;width:550px',
            'required' => true,
            'config'   => $conf,
        );
        $element->addData($config);
        return $element->getElementHtml();
    }
}