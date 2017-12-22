<?php
namespace TemplateMonster\Megamenu\Model\Plugin\Category;

class DataProvider
{
    protected $_storeManager;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;

    }

    public function afterGetData($object, $result)
    {
        foreach ($result as &$item) {
            if (array_key_exists('mm_image', $item)) {
                $data[0]['name'] = $item['mm_image'];
                $data[0]['url'] = $this->getImageUrl($item['mm_image']);
                $item['mm_image'] = $data;
            }
        }
        return $result;
    }

    public function getImageUrl($image)
    {
        $url = false;
        if ($image) {
            if (is_string($image)) {
                $url = $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . 'catalog/category/' . $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }
}