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


class AW_Shopbybrand_Model_Source_Yesno
{
    const CODE_YES  = 1;
    const LABEL_YES = 'Yes';

    const CODE_NO  = 0;
    const LABEL_NO = 'No';

    public function toOptionArray()
    {
        $helper = Mage::helper('awshopbybrand');
        return array(
            array(
                'label' => $helper->__(self::LABEL_YES),
                'value' => self::CODE_YES,
            ),
            array(
                'label' => $helper->__(self::LABEL_NO),
                'value' => self::CODE_NO,
            ),
        );
    }

    public function toOptions()
    {
        $helper = Mage::helper('awshopbybrand');
        return array(
            self::CODE_YES => $helper->__(self::LABEL_YES),
            self::CODE_NO  => $helper->__(self::LABEL_NO),
        );
    }
}