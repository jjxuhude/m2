<?php

namespace TemplateMonster\ProductLabels\Block\Product;

use Magento\CatalogWidget\Block\Product\ProductsList;
use Magento\Catalog\Model\Product;
use TemplateMonster\ProductLabels\Helper\Data;

/**
 * Config edit plugin.
 *
 * @package TemplateMonster\ProductLabels\Block\Product
 */
class WidgetProductsListPlugin
{

    /**
     * @var \TemplateMonster\ProductLabels\Helper\Data
     */
    protected $_helper;

    /**
    * @var \Magento\Framework\View\LayoutInterface
    */
    protected $_layout;

    /**
     * @var \TemplateMonster\ProductLabels\Helper\ProductLabelRule
     */
    protected $_productLabelRule;

    public function __construct(
        Data $helper,
        \Magento\Framework\View\LayoutInterface $layoutInterface,
        \TemplateMonster\ProductLabels\Helper\ProductLabelRule $productLabelRule
    )
    {
        $this->_helper = $helper;
        $this->_layout = $layoutInterface;
        $this->_productLabelRule = $productLabelRule;
    }

    public function aroundGetProductPriceHtml(ProductsList $subject, \Closure $proceed, Product $product)
    {
        $resultHtml = $proceed($product);
        if (!$this->_helper->isActive()) {
            return $resultHtml;
        }
        $labels = $this->_layout->createBlock('Magento\Framework\View\Element\Template')
            ->setProduct($product)
            ->setTemplate('TemplateMonster_ProductLabels::catalog-labels.phtml')->toHtml();
        return $resultHtml.$labels;
    }

    public function afterCreateCollection(ProductsList $subject, $result)
    {
        $collection = $result;
        $productIds = [];

        foreach ($collection->getItems() as $item) {
            $productIds[$item->getid()] = $item->getid();
        }

        if (!$productIds) {
            return $collection;
        }

        $productRulesIds = $this->_productLabelRule->getProductRulesIds($productIds);

        if (!$productRulesIds) {
            return $collection;
        }

        foreach ($collection->getItems() as $item) {
            if (isset($productRulesIds[$item->getid()])) {
                $item->setAppliedRules($productRulesIds[$item->getid()]);
            }
        }

        return $collection;
    }
}

