<?php
/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Model\Filter;

class Stock
{

    /**
     * Store model manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection  $resources
    ) {
        $this->_storeManager = $storeManager;
        $this->_resource = $resources;
    }

    /**
     * @param $collection
     * @return $this
     */
    public function addIsInStockFilterToCollection($collection)
    {
        $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        $websiteId = $this->_storeManager->getStore($collection->getStoreId())->getWebsiteId();
        $joinCondition = $connection->quoteInto(
            'e.entity_id = stock_status_index.product_id' . ' AND stock_status_index.website_id = ?',
            $websiteId
        );

        $joinCondition .= $connection->quoteInto(
            ' AND stock_status_index.stock_id = ?',
            \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID
        );

        $collection->getSelect()->join(
            ['stock_status_index' => 'cataloginventory_stock_status'],
            $joinCondition,
            []
        )->where(
            'stock_status_index.stock_status=?',
            \Magento\CatalogInventory\Model\Stock\Status::STATUS_IN_STOCK
        );
        return $this;
    }

    /**
     * @param $collection
     * @return $this
     */
    public function addIsOutStockFilterToCollection($collection)
    {
        $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        $websiteId = $this->_storeManager->getStore($collection->getStoreId())->getWebsiteId();
        $joinCondition = $connection->quoteInto(
            'e.entity_id = stock_status_index.product_id' . ' AND stock_status_index.website_id = ?',
            $websiteId
        );

        $joinCondition .= $connection->quoteInto(
            ' AND stock_status_index.stock_id = ?',
            \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID
        );

        $collection->getSelect()->join(
            ['stock_status_index' => 'cataloginventory_stock_status'],
            $joinCondition,
            []
        )->where(
            'stock_status_index.stock_status=?',
            \Magento\CatalogInventory\Model\Stock\Status::STATUS_OUT_OF_STOCK
        );
        return $this;
    }

    /**
     * Get connection
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface|false
     */
    public function getConnection()
    {
        $fullResourceName = ($this->connectionName ? $this->connectionName :
                \Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        return $this->_resources->getConnection($fullResourceName);
    }
}
