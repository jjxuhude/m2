<?php
/**
 * Created by PhpStorm.
 * User: impro
 * Date: 28/02/17
 * Time: 14:53
 * Simple product data view
 *
 */
namespace TemplateMonster\ThemeOptions\Block\Product\View;

use \TemplateMonster\ThemeOptions\Helper\Data;

class Gallery extends \Magento\Catalog\Block\Product\View\Gallery
{

    /**
     * ThemeOptions helper.
     *
     * @var helper
     */
    protected $_helper;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Stdlib\ArrayUtils $arrayUtils
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        Data $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $arrayUtils, $jsonEncoder, $data);
    }

    /**
     * Retrieve product images in JSON format
     *
     * @return string
     */
    public function getGalleryImagesJson()
    {
        $imagesItems = [];
        $imagesItemsCount = $this->_helper->getSlidesCount();
        $counter = 1;
        foreach ($this->getGalleryImages() as $image) {
            if(!empty($imagesItemsCount) && $counter > $imagesItemsCount) break;
            $counter++;
            $imagesItems[] = [
                'thumb' => $image->getData('small_image_url'),
                'img' => $image->getData('medium_image_url'),
                'full' => $image->getData('large_image_url'),
                'caption' => $image->getLabel(),
                'position' => $image->getPosition(),
                'isMain' => $this->isMainImage($image),
            ];
        }
        if (empty($imagesItems)) {
            $imagesItems[] = [
                'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
                'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'caption' => '',
                'position' => '0',
                'isMain' => true,
            ];
        }
        return json_encode($imagesItems);
    }

}
