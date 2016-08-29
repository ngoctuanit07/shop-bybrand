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


class AW_Shopbybrand_Model_Source_Displaymode_Productpage
{
    const DISPLAY_NO         = 0;
    const LABEL_NO           = 'No';

    const DISPLAY_BRAND_NAME = 1;
    const LABEL_BRAND_NAME   = 'Show brand name';

    const DISPLAY_BRAND_LOGO = 2;
    const LABEL_BRAND_LOGO   = 'Show brand icon';

    const DISPLAY_BOTH       = 3;
    const LABEL_BOTH         = 'Show brand name and icon';

    public function toOptionArray()
    {
        $helper = Mage::helper('awshopbybrand');
        return array(
            array(
                'label' => $helper->__(self::LABEL_NO),
                'value' => self::DISPLAY_NO,
            ),
            array(
                'label' => $helper->__(self::LABEL_BRAND_NAME),
                'value' => self::DISPLAY_BRAND_NAME,
            ),
            array(
                'label' => $helper->__(self::LABEL_BRAND_LOGO),
                'value' => self::DISPLAY_BRAND_LOGO,
            ),
            array(
                'label' => $helper->__(self::LABEL_BOTH),
                'value' => self::DISPLAY_BOTH,
            ),
        );
    }

    public function toOptions()
    {
        $helper = Mage::helper('awshopbybrand');
        return array(
            self::DISPLAY_NO         => $helper->__(self::LABEL_NO),
            self::DISPLAY_BRAND_NAME => $helper->__(self::LABEL_BRAND_NAME),
            self::DISPLAY_BRAND_LOGO => $helper->__(self::LABEL_BRAND_LOGO),
            self::DISPLAY_BOTH       => $helper->__(self::LABEL_BOTH),
        );
    }
}