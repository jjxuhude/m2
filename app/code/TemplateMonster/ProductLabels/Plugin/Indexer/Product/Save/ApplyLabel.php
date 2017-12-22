<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Plugin\Indexer\Product\Save;

use TemplateMonster\ProductLabels\Model\Indexer\Label\Product\ProductSmartLabelProcessor;

class ApplyLabel
{
    /**
     * @var ProductSmartLabelProcessor
     */
    protected $_productSmartLabelProcessor;

    /**
     * ApplyLabel constructor.
     * @param ProductSmartLabelProcessor $productSmartLabelProcessor
     */
    public function __construct(ProductSmartLabelProcessor $productSmartLabelProcessor)
    {
        $this->_productSmartLabelProcessor = $productSmartLabelProcessor;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product $subject
     * @param callable $proceed
     * @param \Magento\Framework\Model\AbstractModel $product
     * @return mixed
     */
    public function aroundSave(
        \Magento\Catalog\Model\ResourceModel\Product $subject,
        callable $proceed,
        \Magento\Framework\Model\AbstractModel $product
    ) {
        $productResource = $proceed($product);
        if (!$product->getIsMassupdate()) {
            $this->_productSmartLabelProcessor->reindexRow($product->getId());
        }
        return $productResource;
    }
}
