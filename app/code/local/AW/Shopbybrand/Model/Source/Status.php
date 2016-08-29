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


class AW_Shopbybrand_Model_Source_Status
{
    const STATUS_ACTIVE    = 1;
    const LABEL_ACTIVE     = 'Active';

    const STATUS_NOTACTIVE = 0;
    const LABEL_NOTACTIVE  = 'Not Active';

    public function toOptionArray()
    {
        $helper = Mage::helper('awshopbybrand');
        return array(
            array(
                'label' => $helper->__(self::LABEL_NOTACTIVE),
                'value' => self::STATUS_NOTACTIVE,
            ),
            array(
                'label' => $helper->__(self::LABEL_ACTIVE),
                'value' => self::STATUS_ACTIVE,
            ),
        );
    }

    public function toOptions()
    {
        $helper = Mage::helper('awshopbybrand');
        return array(
            self::STATUS_NOTACTIVE => $helper->__(self::LABEL_NOTACTIVE),
            self::STATUS_ACTIVE    => $helper->__(self::LABEL_ACTIVE),
        );
    }
}