<?php

namespace TemplateMonster\ProductLabels\Block\Product;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\LayoutInterface;
use TemplateMonster\ProductLabels\Helper\Data;
use TemplateMonster\ProductLabels\Model\ProductLabel;


/**
 * Config edit plugin.
 *
 * @package TemplateMonster\ProductLabels\Block\Product
 */
class ListProductPlugin
{

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @var \TemplateMonster\ProductLabels\Helper\Data
     */
    protected $_helper;

    public function __construct(
        Data $helper,
        LayoutInterface $layoutInterface
    )
    {
        $this->_helper = $helper;
        $this->_layout = $layoutInterface;
    }

    public function aroundGetProductPrice(ListProduct $subject, \Closure $proceed, Product $product)
    {
        $resultHtml = $proceed($product);
        if (!$this->_helper->isActive() ) {
            return $resultHtml;
        }
        $html = $this->_layout->createBlock('Magento\Framework\View\Element\Template')
            ->setProduct($product)
            ->setTemplate('TemplateMonster_ProductLabels::catalog-labels.phtml')->toHtml();
        return $resultHtml.$html;
    }

}

