<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Helper;

use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\Rule;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Customer\Model\Session as CustomerModelSession;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Customer\Api\GroupManagementInterface;
use TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel;
use TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel\CollectionFactory;

class ProductLabelRule extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var CustomerModelSession
     */
    protected $customerSession;

    /**
     * @var GroupManagementInterface
     */
    protected $groupManagement;


    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager,
        TimezoneInterface $localeDate,
        CustomerModelSession $customerSession,
        GroupManagementInterface $groupManagement,
        ProductLabel $productLabelResource,
        CollectionFactory $productLabelCollectionFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->storeManager = $storeManager;
        $this->localeDate = $localeDate;
        $this->customerSession = $customerSession;
        $this->groupManagement = $groupManagement;
        $this->productLabelResource = $productLabelResource;
        $this->productLabelCollectionFactory = $productLabelCollectionFactory;
        $this->_registry = $registry;
        parent::__construct($context);
    }

    protected function _getCustomerGroupId()
    {
        if ($this->customerSession->isLoggedIn()) {
            $groupId = $this->customerSession->getCustomerGroupId();
        } else {
            $groupId = $this->groupManagement->getNotLoggedInGroup()->getId();
        }
        return $groupId;
    }


    public function getProductRulesIds(array $productIds)
    {
        $store = $this->storeManager->getStore();
        //$websiteId = $store->getWebsiteId();
        $websiteId = $store->getId();
        $groupId = $this->_getCustomerGroupId();

        $productRules = $this->productLabelResource->getProductLabel($websiteId, $groupId, $productIds);
        if (!$productRules) {
            return false;
        }
        $ruleIds = array_column($productRules, 'rule_id');
        $ruleIdsUniq = array_unique($ruleIds);

        $productLabelCollection = $this->productLabelCollectionFactory->create();
        $productLabelCollection->addFieldToFilter('smart_label_id', ['in'=>$ruleIdsUniq]);
        $loadedCollection = $productLabelCollection->load();

        $productRulesIds = [];
        foreach ($productIds as $productId => $key) {
            $sortOrder = [];
            $ruleArr = [];
            foreach ($productRules as $item) {
                if ($productId == $item['product_id']) {
                    $ruleItem = $productLabelCollection->getItemById($item['rule_id']);
                    //Try to check rules by priority
                    if ($ruleItem->getHigherPriority() && !is_null($ruleItem->getPriority())) {
                        $sortOrderValue = current($sortOrder);
                        if (!$sortOrderValue) {
                            $sortOrder = [$ruleItem->getId() => $ruleItem->getPriority()];
                        } elseif ($sortOrderValue < $ruleItem->getPriority()) {
                            $ruleIdLowPriority = key($sortOrder);
                            unset($ruleArr[$ruleIdLowPriority]);
                        }
                    }
                    $ruleArr[$ruleItem->getId()] = $ruleItem->getPriority();
                }
            }
            $productRulesIds[$productId] = $ruleArr;
        }

        if (!$productRulesIds) {
            return false;
        }
        $this->_registry->unregister('smart_label_collection');
        $this->_registry->register('smart_label_collection', $loadedCollection);
        return $productRulesIds;
    }
}
