<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'smart_label_id';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(
            'TemplateMonster\ProductLabels\Model\ProductLabel',
            'TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel');
    }

    /**
     * Find product attribute in conditions or actions
     *
     * @param string $attributeCode
     * @return $this
     */
    public function addAttributeInConditionFilter($attributeCode)
    {
        $match = sprintf('%%%s%%', substr(serialize(['attribute' => $attributeCode]), 5, -1));

        return $this->addFieldToFilter('conditions_serialized', ['like' => $match]);
    }
}
