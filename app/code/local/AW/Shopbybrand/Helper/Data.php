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


class AW_Shopbybrand_Helper_Data extends Mage_Core_Helper_Abstract
{
    const RESIZED_IMAGES_FOLDER = 'resized';

    public function resizeImg($fileName, $width = null, $height = null)
    {
        if ($width === null && $height === null) {
            $height = AW_Shopbybrand_Model_Brand::IMAGE_HEIGHT;
        }
        if ($width === null) {
            $width = AW_Shopbybrand_Model_Brand::IMAGE_WIDTH;
        }

        $baseMediaDir = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $resizedImageFolder = self::RESIZED_IMAGES_FOLDER . DS . $width . "_" . $height;
        $baseImagePath = $baseMediaDir . DS . $fileName;

        $resizedImagePath = $baseMediaDir . DS . $resizedImageFolder . DS . $fileName;
        if (!file_exists($resizedImagePath) && $baseImagePath) {
            $imageProcessor = new Varien_Image($baseImagePath);
            $imageProcessor->constrainOnly(false);
            $imageProcessor->keepAspectRatio(true);
            $imageProcessor->keepFrame(true);
            $imageProcessor->keepTransparency(true);
            $imageProcessor->backgroundColor(array(255, 255, 255));
            $imageProcessor->quality(90);
            $imageProcessor->resize($width, $height);
            $imageProcessor->save($resizedImagePath);
        }

        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $resizedImageFolder . DS . $fileName;
    }
}