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


class AW_Shopbybrand_Block_Adminhtml_Brand_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'brandid';
        $this->_blockGroup = 'awshopbybrand';
        $this->_controller = 'adminhtml_brand';
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('adminhtml')->__('Save And Continue Edit'),
                'onclick' => "saveAndContinueEdit('{$this->_getSaveAndContinueUrl()}')",
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            Event.observe(window, 'load', function(){
                if ($('buttonsbrandpage_description')) {
                    $('buttonsbrandpage_description').children[1].remove()
                }
            });

            function toggleEditor() {
                if (tinyMCE.getInstanceById('brandpage_description') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'brandpage_description');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'brandpage_description');
                }
            }

            function saveAndContinueEdit(url) {
                editForm.submit(url.replace(/{{tab_id}}/ig,brand_info_tabsJsTabs.activeTab.id));
            }
        ";
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            '*/*/save',
            array(
                '_current' => true,
                'back'     => 'edit',
                'tab'      => '{{tab_id}}',
            )
        );
    }

    public function getHeaderText()
    {
        $id = $this->getRequest()->getParam('id', null);
        if ($id === null) {
            return $this->__('New Brand');
        }
        $data = Mage::getModel('awshopbybrand/brand')->load($id);
        return $this->__("Edit Brand '%s'", $data->getTitle());
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
}