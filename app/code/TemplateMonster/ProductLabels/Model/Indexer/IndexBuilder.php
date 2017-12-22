<?php

namespace TemplateMonster\ProductLabels\Model\Indexer;

use Magento\Store\Model\StoreManagerInterface;
use TemplateMonster\ProductLabels\Model\ProductLabel;

class IndexBuilder
{
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var \TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel\CollectionFactory
     */
    protected $_labelCollectionFactory;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $_connection;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var
     */
    protected $_loadedProducts;

    /**
     * @var int
     */
    protected $_batchCount;

    /**
     * IndexBuilder constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel\CollectionFactory $labelCollectionFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param int $batchCount
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResourceConnection $resource,
        \TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel\CollectionFactory $labelCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Psr\Log\LoggerInterface $logger,
        $batchCount = 2000
    ) {
        $this->_storeManager = $storeManager;
        $this->_resource = $resource;
        $this->_labelCollectionFactory = $labelCollectionFactory;
        $this->_connection = $resource->getConnection();
        $this->_productFactory = $productFactory;
        $this->_logger = $logger;
        $this->_batchCount = $batchCount;
    }

    /**
     * @param $id
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function reindexById($id)
    {
        $this->reindexByIds([$id]);
    }

    /**
     * @param $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function reindexByIds($ids)
    {
        try {
            $this->doReindexByIds($ids);
        } catch (\Exception $e) {
            $this->_critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
        }
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function reindexFull()
    {
        try {
            $this->doReindexFull();
        } catch (\Exception $e) {
            $this->_critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
        }
    }

    /**
     * @param array $ids
     */
    protected function doReindexByIds(array $ids)
    {
        $this->cleanByIds($ids);
        foreach ($this->getAllRules() as $rule) {
            foreach ($ids as $productId) {
                $this->applyLabel($rule, $this->getProduct($productId));
            }
        }
    }


    public function doReindexFull()
    {
        foreach ($this->getAllRules() as $rule) {
            $this->updateLabelProductData($rule);
        }
    }

    /**
     * @param ProductLabel $rule
     * @return $this
     */
    public function updateLabelProductData(ProductLabel $rule)
    {
        $ruleId = $rule->getId();
        if ($rule->getProductsFilter()) {
            $this->_connection->delete(
                $this->getTable('smart_label_rule_product'),
                ['rule_id=?' => $ruleId, 'product_id IN (?)' => $rule->getProductsFilter()]
            );
        } else {
            $this->_connection->delete(
                $this->getTable('smart_label_rule_product'),
                $this->_connection->quoteInto('rule_id=?', $ruleId)
            );
        }
        //Add Is Active Params
        /**
        if (!$rule->getIsActive()) {
            return $this;
        }
        **/

        if ($rule->getWebsiteIds() == 0) {
            $storeIds = $this->_getAllStoreIds();
        }
        else {
            $storeIds = array_map('intval', explode(',', $rule->getWebsiteIds()));
        }
        if (empty($storeIds)) {
            return $this;
        }
        if ($rule->getUseCustomerGroup()) {
            $customerGroupIds = $rule->getCustomerGroupIds();
            if (!is_array($customerGroupIds)) {
                $customerGroupIds = explode(',', $customerGroupIds);
            }
        } else {
            $customerGroupIds[] = null;
        }

        $ruleId = $rule->getId();
        $productIds = $rule->getMatchingProductIds();

        try {
            foreach ($productIds as $productId => $validationByWebsite) {
                    foreach($storeIds as $storeViewId) {
                        foreach ($customerGroupIds as $customerGroupId) {
                            $rows[] = [
                                'rule_id' => $ruleId,
                                'website_id' => $storeViewId,
                                'customer_group_id' => $customerGroupId,
                                'product_id' => $productId,
                            ];

                            if (count($rows) == $this->_batchCount) {
                                $this->_connection->insertMultiple($this->getTable('smart_label_rule_product'), $rows);
                                $rows = [];
                            }
                        }
                    }
                }
//            }

            if (!empty($rows)) {
                $this->_connection->insertMultiple($this->getTable('smart_label_rule_product'), $rows);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * @param ProductLabel $rule
     * @param $product
     * @return $this
     */
    public function applyLabel(ProductLabel $rule, $product)
    {
        $ruleId = $rule->getId();
        $productId = $product->getId();

        if ($rule->getWebsiteIds() == 0) {
            $storeIds = $this->_getAllStoreIds();
        }
        else {
            $storeIds = array_map('intval', explode(',', $rule->getWebsiteIds()));
        }

        if ($rule->getUseCustomerGroup()) {
            $customerGroupIds = $rule->getCustomerGroupIds();
            if (!is_array($customerGroupIds)) {
                $customerGroupIds = explode(',', $customerGroupIds);
            }
        } else {
            $customerGroupIds[] = null;
        }

        $this->_connection->delete(
            $this->_resource->getTableName('smart_label_rule_product'),
            [
                $this->_connection->quoteInto('rule_id = ?', $ruleId),
                $this->_connection->quoteInto('product_id = ?', $productId)
            ]
        );

        try {
            foreach($storeIds as $storeViewId) {
                foreach ($customerGroupIds as $customerGroupId) {
                    $websiteId = $this->_storeManager->getStore($storeViewId)->getWebsiteId();
                    if (!$rule->validate($product) || !$rule->productHasLabel($productId,$customerGroupId, $websiteId)) {
                        continue;
                    }
                    $rows[] = [
                        'rule_id' => $ruleId,
                        'website_id' => $storeViewId,
                        'customer_group_id' => $customerGroupId,
                        'product_id' => $productId,
                    ];

                    if (count($rows) == $this->_batchCount) {
                        $this->_connection->insertMultiple($this->getTable('smart_label_rule_product'), $rows);
                        $rows = [];
                    }
                }
            }

            if (!empty($rows)) {
                $this->_connection->insertMultiple($this->getTable('smart_label_rule_product'), $rows);
            }
        } catch (\Exception $e) {
        }
        return $this;
    }

    /**
     * @param $productIds
     */
    protected function cleanByIds($productIds)
    {
        $this->_connection->deleteFromSelect(
            $this->_connection
                ->select()
                ->from($this->_resource->getTableName('smart_label_rule_product'), 'product_id')
                ->distinct()
                ->where('product_id IN (?)', $productIds),
            $this->getTable('catalogrule_product_price')
        );
    }

    /**
     * @param string $tableName
     * @return string
     */
    protected function getTable($tableName)
    {
        return $this->_resource->getTableName($tableName);
    }

    /**
     * @param int $productId
     * @return Product
     */
    protected function getProduct($productId)
    {
        if (!isset($this->_loadedProducts[$productId])) {
            $this->_loadedProducts[$productId] = $this->_productFactory->create()->load($productId);
        }
        return $this->_loadedProducts[$productId];
    }

    /**
     * @param \Exception $e
     * @return void
     */
    protected function _critical($e)
    {
        $this->_logger->critical($e);
    }

    /**
     * @return mixed
     */
    public function getAllRules()
    {
        return $this->_labelCollectionFactory->create();
    }

    /**
     * @return array
     */
    protected function _getAllStoreIds()
    {
        return array_keys($this->_storeManager->getStores());
    }
}
