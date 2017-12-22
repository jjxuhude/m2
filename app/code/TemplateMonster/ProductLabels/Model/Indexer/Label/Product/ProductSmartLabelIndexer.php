<?php

namespace TemplateMonster\ProductLabels\Model\Indexer\Label\Product;

class ProductSmartLabelIndexer extends \TemplateMonster\ProductLabels\Model\Indexer\AbstractIndexer
{

    /**
     * @param \int[] $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function doExecuteList($ids)
    {
        $this->_indexBuilder->reindexByIds(array_unique($ids));
    }

    /**
     * @param int $id
     */
    protected function doExecuteRow($id)
    {
        $this->_indexBuilder->reindexById($id);
    }
}
