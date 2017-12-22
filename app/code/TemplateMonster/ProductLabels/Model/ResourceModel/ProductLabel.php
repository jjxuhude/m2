<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductLabel extends AbstractDb
{

    protected $_fieldWithArray = ['customer_group_ids','website_ids'];

    protected function _construct()
    {
        $this->_init('smart_label_product', 'smart_label_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_fieldWithArrayToString($object);
        return parent::_beforeSave($object);
    }

    /**
     *
     * Convert Array to Comma Separated
     *
     * @param $object
     */
    protected function _fieldWithArrayToString($object)
    {
        foreach ($this->_fieldWithArray as $fieldName) {
            $value = $object->getData($fieldName);
            if ($value && is_array($value)) {
                $commaSeparated = implode(",", $value);
                $object->setData($fieldName, $commaSeparated);
            }
        }
    }

    public function getProductLabel($websiteId, $customerGroupId, $productIds)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('smart_label_rule_product'),
            ['product_id', 'rule_id']
        );
        $select->where(
            'website_id = ?',
            $websiteId
        );

        $select->where(
            'customer_group_id = ? or customer_group_id IS NULL',
            $customerGroupId
        );

        $select->where(
            'product_id IN(?)',
            $productIds
        );

        return $connection->fetchAll($select);
    }
}
