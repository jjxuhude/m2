<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Observer;

use Magento\Catalog\Model\Product;
use Magento\CatalogRule\Model\Rule;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Customer\Model\Session as CustomerModelSession;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Event\ObserverInterface;
use TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel;
use TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel\CollectionFactory;

class ProductListCollectionObserver implements ObserverInterface
{
    /**
     * @var
     */
    protected $_productLabelRule;


    /**
     * ProductListCollectionObserver constructor.
     * @param StoreManagerInterface $storeManager
     * @param TimezoneInterface $localeDate
     * @param CustomerModelSession $customerSession
     * @param GroupManagementInterface $groupManagement
     */
    public function __construct(
        \TemplateMonster\ProductLabels\Helper\ProductLabelRule $productLabelRule
    ) {
        $this->_productLabelRule = $productLabelRule;
    }

    protected function _getCustomerGroupId($observer)
    {
        if ($observer->getEvent()->hasCustomerGroupId()) {
            $groupId = $observer->getEvent()->getCustomerGroupId();
        } else {
            if ($this->customerSession->isLoggedIn()) {
                $groupId = $this->customerSession->getCustomerGroupId();
            } else {
                $groupId = $this->groupManagement->getNotLoggedInGroup()->getId();
            }
        }
        return $groupId;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        /* @var $collection ProductCollection */
        $collection = $observer->getEvent()->getCollection();

        //Get product ids on collection
        $productIds = [];
        //TODO: Need to test perfomace with: overwrite of public function AbstractCollection::getAllIds()
        foreach ($collection->getItems() as $item) {
            $productIds[$item->getid()] = $item->getid();
        }

        if (!$productIds) {
            return $this;
        }

        /**
        $store = $this->storeManager->getStore($observer->getEvent()->getStoreId());
        $websiteId = $store->getWebsiteId();
        $groupId = $this->_getCustomerGroupId($observer);
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
        **/

        $productRulesIds = $this->_productLabelRule->getProductRulesIds($productIds);

        if (!$productRulesIds) {
            return $this;
        }

        //TODO: Need to test perfomace with: overwrite of public function AbstractCollection::getAllIds()
        foreach ($collection->getItems() as $item) {
            if (isset($productRulesIds[$item->getid()])) {
                $item->setAppliedRules($productRulesIds[$item->getid()]);
            }
        }
    }
}
