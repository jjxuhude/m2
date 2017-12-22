<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Model\Indexer\Label\SmartLabel;

class SmartLabelProductIndexer extends \TemplateMonster\ProductLabels\Model\Indexer\AbstractIndexer
{
    /**
     * @param \int[] $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function doExecuteList($ids)
    {
        $this->_indexBuilder->reindexFull();
    }

    /**
     * @param int $id
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function doExecuteRow($id)
    {
        $this->_indexBuilder->reindexFull();
    }
}
