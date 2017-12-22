<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Plugin\Indexer;

use TemplateMonster\ProductLabels\Model\Indexer\Label\Product\ProductSmartLabelProcessor;

class Category
{
    /**
     * @var ProductSmartLabelProcessor
     */
    protected $_productSmartLabelProcessor;

    /**
     * Category constructor.
     * @param ProductSmartLabelProcessor $productSmartLabelProcessor
     */
    public function __construct(ProductSmartLabelProcessor $productSmartLabelProcessor)
    {
        $this->_productSmartLabelProcessor = $productSmartLabelProcessor;
    }

    /**
     * @param \Magento\Catalog\Model\Category $subject
     * @param \Magento\Catalog\Model\Category $result
     * @return \Magento\Catalog\Model\Category
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        \Magento\Catalog\Model\Category $subject,
        \Magento\Catalog\Model\Category $result
    ) {
        /** @var \Magento\Catalog\Model\Category $result */
        $productIds = $result->getAffectedProductIds();
        if ($productIds) {
            $this->_productSmartLabelProcessor->reindexList($productIds);
        }
        return $result;
    }

    /**
     * @param \Magento\Catalog\Model\Category $subject
     * @param \Magento\Catalog\Model\Category $result
     * @return \Magento\Catalog\Model\Category
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        \Magento\Catalog\Model\Category $subject,
        \Magento\Catalog\Model\Category $result
    ) {
        $this->_productSmartLabelProcessor->markIndexerAsInvalid();
        return $result;
    }
}
