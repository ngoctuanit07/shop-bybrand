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

$this->startSetup();
try {
    $this->run("
        ALTER TABLE `{$this->getTable('awshopbybrand/brand')}`
            ADD COLUMN `page_title` varchar(255) after `title`;
        ALTER TABLE `{$this->getTable('awshopbybrand/brand')}`
            ADD COLUMN `is_show_in_sidebar` TINYINT NOT NULL DEFAULT 1 AFTER `brand_status`;
    ");
} catch (Exception $e) {
    Mage::logException($e);
}
$this->endSetup();