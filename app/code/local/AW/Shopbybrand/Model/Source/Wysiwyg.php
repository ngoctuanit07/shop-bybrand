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


class AW_Shopbybrand_Model_Source_Wysiwyg extends Mage_Cms_Model_Wysiwyg_Config
{
    public function getConfig($data = array())
    {
        $config = parent::getConfig($data);
        $urlModel = Mage::getSingleton('adminhtml/url');
        $config->addData(
            array(
                'files_browser_window_url' => $urlModel->getUrl('adminhtml/cms_wysiwyg_images/index/'),
                'directives_url'           => $urlModel->getUrl('adminhtml/cms_wysiwyg/directive'),
                'directives_url_quoted'    => preg_quote($config->getData('directives_url')),
                'widget_window_url'        => $urlModel->getUrl('adminhtml/widget/index'),
            )
        );
        return $config;
    }
}