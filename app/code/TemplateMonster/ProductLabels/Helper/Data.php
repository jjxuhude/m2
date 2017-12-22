<?php

namespace TemplateMonster\ProductLabels\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    /**
     * Is enable labels.
     */
    const XML_PATH_ACTIVE = 'productlabel/config/active';

    /**
     * Product Container config.
     */
    const XML_PATH_PRODUCT_CONTAINER = 'productlabel/config/product_container';

    /**
     * Category Container config.
     */
    const XML_PATH_CATEGORY_CONTAINER = 'productlabel/config/category_container';


    /**
     * Check is module enabled.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get product container
     *
     * @return string
     */
    public function getProductContainer()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_CONTAINER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get category container
     *
     * @return string
     */
    public function getCategoryContainer()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CATEGORY_CONTAINER,
            ScopeInterface::SCOPE_STORE
        );
    }


    /**
     * Get resize image url .
     *
     * @return string
     */
    public function resizeImageUrl($image, $width = null, $height = null, $type = 'logo')
    {
        if(empty($image)) {
            return false;
        }
        $path = 'logo/logo/';
        if ($type == 'banner') {
            $path = 'brandpage/brandpage/';
        } elseif ($type == 'product-logo') {
            $path = 'brandproductpage/brandproductpage/';
        }
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath($path).$image;
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath($path . 'resized/'.$width.'/').$image;
        //create image factory...
        $imageResize = $this->_imageFactory->create();
        $imageResize->open($absolutePath);
        $imageResize->constrainOnly(TRUE);
        $imageResize->keepTransparency(TRUE);
        $imageResize->keepFrame(FALSE);
        $imageResize->keepAspectRatio(TRUE);
        $imageResize->resize($width,$height);
        //destination folder
        $destination = $imageResized ;
        //save image
        $imageResize->save($destination);

        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).$path.'resized/'.$width.'/'.$image;
        return $resizedURL;
    }

}