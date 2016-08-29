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
        ALTER TABLE `{$this->getTable('awshopbybrand/brand')}` ADD UNIQUE (`url_key`);
        CREATE TABLE IF NOT EXISTS `{$this->getTable('awshopbybrand/brand_product')}` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `brand_id` int(10) unsigned NOT NULL DEFAULT 0,
            `product_id` int(10) unsigned NOT NULL DEFAULT 0,
            `position` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`),
            KEY `product_id` (`product_id`),
            KEY `brand_id` (`brand_id`),
            CONSTRAINT `FK_link_to_brand` FOREIGN KEY (`brand_id`)
                REFERENCES {$this->getTable('awshopbybrand/brand')} (`id`)
                ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `FK_link_to_product` FOREIGN KEY (`product_id`)
               REFERENCES {$this->getTable('catalog/product')} (`entity_id`)
               ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ");
} catch (Exception $ex) {
    Mage::logException($ex);
}
$this->endSetup();