<?php

namespace TemplateMonster\ProductLabels\Helper;

use Magento\Directory\Model\PriceCurrency;

class LabelOutput extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_product;

    protected $_registry;

    public $_storeManager;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        PriceCurrency $priceCurrency
    ) {
        $this->_registry = $registry;
        $this->_product = $this->getProductFromRegistry();
        $this->_storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context);
    }

    public function setProduct($product)
    {
        $this->_product = $product;
    }

    public function getCollection()
    {
        return $this->_registry->registry('smart_label_collection');
    }

    public function getValueByAttrName($name)
    {
        return $this->_product->getData($name);
    }

    public function getSavePercent()
    {
        if ($this->_product->getPrice() && $this->_product->getFinalPrice()) {
            $savePercent =  ceil(
                100 - ((100 / $this->_product->getPrice())
                    * $this->_product->getFinalPrice())
            );
        } else {
            $savePercent = 0;
        }

        return __('<strong class="benefit"><span class="save">save </span><span class="value tier-%1">%1</span><span class="percent">%</span></strong>', $savePercent);
    }

    public function getSaveAmount()
    {
        if ($this->_product->getPrice() <= $this->_product->getFinalPrice()) {
            return false;
        }
        $price = $this->_product->getPrice() - $this->_product->getFinalPrice();
        $priceInCurrentCurrency = $this->priceCurrency->convertAndRound($price);
        return $this->priceCurrency->format($priceInCurrentCurrency);
    }

    public function getPrice()
    {
        return $this->_product->getPrice();
    }

    public function getFormatedPrice()
    {
        return $this->_product->getFormatedPrice();
    }

    public function getSpecialPrice()
    {
        $specialPrice = $this->_product->getSpecialPrice();
        return $this->priceCurrency->format($specialPrice);
    }

    public function getNewFor()
    {
        $created = $this->_product->getCreatedAt();

        $dStart = new \DateTime();
        $dEnd  = new \DateTime($created);
        $dDiff = $dStart->diff($dEnd);

        if ($dDiff->days <= 0) {
            return false;
        }

        return $dDiff->days;
    }

    public function getSku()
    {
        return $this->_product->getData('sku');
    }

    public function parseStringForReplace($text)
    {
        if (!preg_match('#\((.*?)\)#', $text, $match)) {
            return false;
        }
    }

    public function attrCode($string)
    {
        $result = str_replace(['{', '}', ':', 'ATTR'], '', $string);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function breakString($string)
    {
        return str_replace('{BR}', '<br/>', $string);
    }


    public function replaceString($string)
    {
        switch ($string) {

             case $this->findSubstring($string, "{SAVE_PERCENT}"):
                 return $this->replaceSubstring("{SAVE_PERCENT}", $this->getSavePercent(), $string);

             case $this->findSubstring($string, "{SAVE_AMOUNT}"):
                 return $this->replaceSubstring("{SAVE_AMOUNT}", $this->getSaveAmount(), $string);

             case $this->findSubstring($string, "{PRICE}"):
                 return $this->replaceSubstring("{PRICE}", $this->getFormatedPrice(), $string);

             case $this->findSubstring($string, "{SPECIAL_PRICE}"):
                 return $this->replaceSubstring("{SPECIAL_PRICE}", $this->getSpecialPrice(), $string);

             case $this->findSubstring($string, "{NEW_FOR}"):
                 return $this->replaceSubstring("{NEW_FOR}", $this->getNewFor(), $string);

             case $this->findSubstring($string, "{SKU}"):
                 return $this->replaceSubstring("{SKU}", $this->getSku(), $string);

             default:
                 if (strpos($string, 'ATTR') !== false) {
                     return $this->attrCode($string);
                 } elseif (strpos($string, '{BR}')) {
                     return $this->breakString($string);
                 } else {
                     return $string;
                 }
         }
    }

    protected function findSubstring($string, $substring)
    {
        return (strpos($string, $substring) !== false) ? true : false;
    }

    protected function replaceSubstring($substring, $replacement, $string) {
        return str_replace($substring, $replacement, $string);
    }

    public function getMedia()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getLabelPosition($pos)
    {
        $position = [
            'top'    => '0',
            'right'  => 'auto',
            'bottom' => 'auto',
            'left'   => '0',
            'top_transform' => '0',
            'left_transform' => '0'
        ];

        if ($pos == 'right_top'){
            $position = [
                'top'    => '0',
                'right'  => '0',
                'bottom' => 'auto',
                'left'   => 'auto',
                'top_transform' => '0',
                'left_transform' => '0'
            ];
        } elseif ($pos == 'center_top'){
            $position = [
                'top'    => '0',
                'right'  => 'auto',
                'bottom' => 'auto',
                'left'   => '50%',
                'top_transform' => '0',
                'left_transform' => '50%'
            ];
        } elseif ($pos == 'right_middle'){
            $position = [
                'top'    => '50%',
                'right'  => '0',
                'bottom' => 'auto',
                'left'   => 'auto',
                'top_transform' => '50%',
                'left_transform' => '0'
            ];
        } elseif ($pos == 'left_middle'){
            $position = [
                'top'    => '50%',
                'right'  => 'auto',
                'bottom' => 'auto',
                'left'   => '0',
                'top_transform' => '50%',
                'left_transform' => '0'
            ];
        } elseif ($pos == 'center_middle'){
            $position = [
                'top'    => '50%',
                'right'  => 'auto',
                'bottom' => 'auto',
                'left'   => '50%',
                'top_transform' => '50%',
                'left_transform' => '50%'
            ];
        } elseif ($pos == 'right_bottom'){
            $position = [
                'top'    => 'auto',
                'right'  => '0',
                'bottom' => '0',
                'left'   => 'auto',
                'top_transform' => '0',
                'left_transform' => '0'
            ];
        } elseif ($pos == 'left_bottom'){
            $position = [
                'top'    => 'auto',
                'right'  => 'auto',
                'bottom' => '0',
                'left'   => '0',
                'top_transform' => '0',
                'left_transform' => '0'
            ];
        } elseif ($pos == 'center_bottom') {
            $position = [
                'top'    => 'auto',
                'right'  => 'auto',
                'bottom' => '0',
                'left'   => '50%',
                'top_transform' => '0',
                'left_transform' => '50%'
            ];
        };
       return $position;
    }

    public function getProductFromRegistry()
    {
        return $this->_registry->registry('product');
    }

    public function getProduct()
    {
        return $this->_product;
    }
}
