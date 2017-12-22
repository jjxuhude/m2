<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Model;

use Magento\Framework\Model\AbstractModel;
use TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface;

class ProductLabel extends \Magento\Rule\Model\AbstractModel implements ProductLabelInterface
{


    const STATUS_NOT_USE = 0;

    const STATUS_NOT = 1;

    const STATUS_YES = 2;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\CatalogRule\Model\Rule\Condition\CombineFactory
     */
    protected $_combineFactory;

    /**
     * @var \Magento\CatalogRule\Model\Rule\Action\CollectionFactory
     */
    protected $_actionCollectionFactory;

    /**
     * @var
     */
    protected $_productIds;

    /**
     * @var
     */
    protected $_productsFilter;

    /**
     * @var \Magento\Framework\Model\ResourceModel\Iterator
     */
    protected $_resourceIterator;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var
     */
    protected $_websitesMap;

    /**
     *
     */

    protected $_storesViewMap;

    /**
     * @var Indexer\Label\SmartLabel\SmartLabelProductProcessor
     */
    protected $_ruleProductProcessor;

    /**
     * @var Filter\IsOnSale
     */
    protected $_isOnSale;

    /**
     * @var Filter\IsNew
     */
    protected $_isNew;

    /**
     * @var
     */
    protected $_stockStatus;

    /**
     * @var Filter\Price
     */
    protected $_price;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypesList;

    /**
     * @var array
     */
    protected $_relatedCacheTypes;


    /**
     * ProductLabel constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory
     * @param \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Framework\Model\ResourceModel\Iterator $resourceIterator
     * @param Filter\UseDateRange $filterDataRange
     * @param Filter\IsOnSale $isOnSale
     * @param Filter\IsNew $isNew
     * @param Filter\Stock $stock
     * @param Filter\Price $price
     * @param \Magento\CatalogInventory\Helper\Stock $stockStatusHelper
     * @param Indexer\Label\SmartLabel\SmartLabelProductProcessor $ruleProductProcessor
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypesList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $relatedCacheTypes
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory,
        \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Model\ResourceModel\Iterator $resourceIterator,
        \TemplateMonster\ProductLabels\Model\Filter\UseDateRange $filterDataRange,
        \TemplateMonster\ProductLabels\Model\Filter\IsOnSale $isOnSale,
        \TemplateMonster\ProductLabels\Model\Filter\IsNew $isNew,
        \TemplateMonster\ProductLabels\Model\Filter\Stock $stock,
        \TemplateMonster\ProductLabels\Model\Filter\Price $price,
        \Magento\CatalogInventory\Helper\Stock $stockStatusHelper,
        \TemplateMonster\ProductLabels\Model\Indexer\Label\SmartLabel\SmartLabelProductProcessor $ruleProductProcessor,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypesList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $relatedCacheTypes = [],
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_productFactory = $productFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_combineFactory = $combineFactory;
        $this->_actionCollectionFactory = $actionCollectionFactory;
        $this->_resourceIterator = $resourceIterator;
        $this->_filterDataRange = $filterDataRange;
        $this->_isOnSale = $isOnSale;
        $this->_isNew = $isNew;
        $this->_stock = $stock;
        $this->_price = $price;
        $this->_stockStatusHelper = $stockStatusHelper;
        $this->_ruleProductProcessor = $ruleProductProcessor;
        $this->_cacheTypesList = $cacheTypesList;
        $this->_relatedCacheTypes = $relatedCacheTypes;
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        $this->_init('TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel');
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws \Exception
     */
    protected function _getFilteredCollection($customerGroupId = null, $websiteId = null)
    {
        if ($this->getWebsiteIds() == 0) {
            $storeIds = $this->_getAllStoreIds();
        }
        else {
            $storeIds = array_map('intval', explode(',', $this->getWebsiteIds()));
        }
//        $storeIds = array_map('intval', explode(',', $this->getWebsiteIds()));
        $websiteIds = [];
        foreach ($storeIds as $storeId) {
            $websiteIds[] = $this->_storeManager->getStore($storeId)->getWebsiteId();
        }

        /** @var $productCollection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addWebsiteFilter($websiteIds);
        if ($this->_productsFilter) {
            $productCollection->addIdFilter($this->_productsFilter);
        }

        $this->getConditions()->collectValidatedAttributes($productCollection);
        //$productCollection->addFinalPrice();
        $productCollection->addPriceData($customerGroupId, $websiteId);
        //Date range condition
        $dateCondition = $this->_filterDataRange->getDateRangeCondition($this);
        if ($dateCondition) {
            $productCollection->addAttributeToFilter('created_at', $dateCondition);
        }

        //Is New Product Condition
        if ($this->getIsNew() == self::STATUS_NOT) {
            $this->_isNew->isNotNew($productCollection);
        } elseif ($this->getIsNew() == self::STATUS_YES) {
            $this->_isNew->isNew($productCollection);
        }

        //Is On Sale Condition
        if ($this->getIsOnSale() == self::STATUS_NOT) {
            $this->_isOnSale->notOnSale($productCollection);
        } elseif ($this->getIsOnSale() == self::STATUS_YES) {
            $this->_isOnSale->onSale($productCollection);
        }


        //https://github.com/magento/magento2/issues/5236

        //Is In Stock Condition
        /**
        if ($this->getStockStatus() == self::STATUS_NOT) {
            $this->_stock->addIsOutStockFilterToCollection($productCollection);
        } elseif ($this->getStockStatus() == self::STATUS_YES) {
            $this->_stock->addIsInStockFilterToCollection($productCollection);
        }**/

        //Use Price Range Condition For Price Type
        if ($this->getUsePriceRange()) {
            $this->_price->addPriceFilter($productCollection, $this->getByPrice(), $this->getFromPrice(),
                $this->getToPrice());
        }
        return $productCollection;
    }

    /**
     * Invalidate related cache types
     *
     * @return $this
     */
    protected function _invalidateCache()
    {
        if (count($this->_relatedCacheTypes)) {
            $this->_cacheTypesList->invalidate($this->_relatedCacheTypes);
        }
        return $this;
    }

    /**
     * @param $productId
     * @return bool
     */
    public function productHasLabel($productId,$customerGroupId = null, $websiteId = null)
    {
        $productCollection = $this->_getFilteredCollection($customerGroupId, $websiteId);
        $productCollection->addAttributeToFilter('entity_id', $productId);
        return (boolean)$productCollection->getSize();
    }


    /**
     * Get array of product ids which are matched by rule
     *
     * @return array
     */
    public function getMatchingProductIds()
    {
        if ($this->_productIds === null) {
            $this->_productIds = [];
            $this->setCollectedAttributes([]);
            $productCollection = $this->_getFilteredCollection();
            $this->_resourceIterator->walk(
                $productCollection->getSelect(),
                [[$this, 'callbackValidateProduct']],
                [
                    'attributes' => $this->getCollectedAttributes(),
                    'product' => $this->_productFactory->create()
                ]
            );
        }
        return $this->_productIds;
    }

    /**
     * Callback function for product matching
     *
     * @param array $args
     * @return void
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);
        $websites = $this->_getWebsitesMap();
        $results = [];

        foreach ($websites as $websiteId => $defaultStoreId) {
            $product->setStoreId($defaultStoreId);
            //$results[$defaultStoreId] = $this->getConditions()->validate($product);
            $results[$websiteId] = $this->getConditions()->validate($product);
        }
        $this->_productIds[$product->getId()] = $results;
    }

    /**
     * Prepare website map
     *
     * @return array
     */
    protected function _getWebsitesMap()
    {
        if (!$this->_websitesMap) {
            $websiteIds = $this->getWebsiteIds();

            if ($websiteIds == 0) {
                $map = [];
                $websites = $this->_storeManager->getWebsites(true);
                foreach ($websites as $website) {
                    // Continue if website has no store to be able to create catalog rule for website without store
                    if ($website->getDefaultStore() === null) {
                        continue;
                    }
                    $map[$website->getId()] = $website->getDefaultStore()->getId();
                }
                $this->_websitesMap = $map;
            } else {
                $storeViewIds = explode(',', $websiteIds);
                foreach ($storeViewIds as $storeViewId) {
                    $store = $this->_storeManager->getStore($storeViewId);
                    $this->_websitesMap[$store->getWebsiteId()] = [$store->getStoreId()];
                }
            }
        }

        return $this->_websitesMap;
    }

    protected function getStoreViewsMap()
    {
        if( !$this->_storesViewMap) {
            $websiteIds = $this->getWebsiteIds();
            if ($websiteIds == 0) {
                foreach ($this->_storeManager->getWebsites() as $website) {
                    $websiteId = $website->getWebsiteId();
                    $storesArr = [];
                    foreach ($this->_storeManager->getStores() as $store) {
                        if(($websiteId != $store->getWebsiteId()) && $store->getIsActive())
                        {
                            continue;
                        }
                        $storesArr[] = $store->getStoreId();
                    }
                    $this->_storesViewMap[$websiteId] = $storesArr;
                }
            } else {
                $store = $this->_storeManager->getStore($websiteIds);
                $this->_storesViewMap[$store->getWebsiteId()] = [$store->getStoreId()];
            }
        }
        return $this->_storesViewMap;
    }

    public function getStoreViewsByWebsiteId($websiteId)
    {
        $result = [];
        if (!$this->getStoreViewsMap() || !is_array($this->getStoreViewsMap())) {
            return $result;
        }

        $maps = $this->getStoreViewsMap();
        if(isset($maps[$websiteId]))
        {
            return $result = $maps[$websiteId];
        }
        return $result;
    }


    /**
     * @return $this
     */
    public function afterSave()
    {
        if ($this->isObjectNew()) {
            $this->getMatchingProductIds();
            if (!empty($this->_productIds) && is_array($this->_productIds)) {
                $this->_ruleProductProcessor->reindexList($this->_productIds);
            }
        } else {
            $this->_ruleProductProcessor->getIndexer()->invalidate();
        }
        return parent::afterSave();
    }

    /**
     * @return $this
     */
    public function afterDelete()
    {
        $this->_ruleProductProcessor->getIndexer()->invalidate();
        return parent::afterDelete();
    }

    public function getPreparedWebsiteIds()
    {
        if(!$this->_getWebsitesMap())
        {
            return [];
        }
        $result = array_keys($this->_getWebsitesMap());
        return $result;
    }


    public function setName($name)
    {
        $this->setData(self::NAME, $name);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setPriority($priority)
    {
        $this->setData(self::PRIORITY, $priority);
    }

    public function getPriority()
    {
        return $this->getData(self::PRIORITY);
    }

    public function setHigherPriority($higherPriority)
    {
        $this->setData(self::HIGHER_PRIORITY, $higherPriority);
    }

    public function getHigherPriority()
    {
        return $this->getData(self::HIGHER_PRIORITY);
    }

    public function setUseForParent($useForParent)
    {
        $this->setData(self::USE_FOR_PARENT, $useForParent);
    }

    public function getUseForParent()
    {
        return $this->getData(self::USE_FOR_PARENT);
    }

    public function setWebsiteIds($websiteIds)
    {
        $this->setData(self::WEBSITE_IDS, $websiteIds);
    }

    public function getWebsiteIds()
    {
        return $this->getData(self::WEBSITE_IDS);
    }

    public function setProductLabelStatus($productLabelStatus)
    {
        $this->setData(self::PRODUCT_LABEL_STATUS, $productLabelStatus);
    }

    public function getProductLabelStatus()
    {
        return $this->getData(self::PRODUCT_LABEL_STATUS);
    }

    public function setProductLabelType($productLabelType)
    {
        $this->setData(self::PRODUCT_LABEL_TYPE, $productLabelType);
    }

    public function getProductLabelType()
    {
        return $this->getData(self::PRODUCT_LABEL_TYPE);
    }

    public function setProductImageLabel($productImageLabel)
    {
        $this->setData(self::PRODUCT_IMAGE_LABEL, $productImageLabel);
    }

    public function getProductImageLabel()
    {
        return $this->getData(self::PRODUCT_IMAGE_LABEL);
    }

    public function setProductImagePosition($productImagePosition)
    {
        $this->setData(self::PRODUCT_IMAGE_POSITION, $productImagePosition);
    }

    public function getProductImagePosition()
    {
        return $this->getData(self::PRODUCT_IMAGE_POSITION);
    }

    public function setProductImageContainer($productImageContainer)
    {
        $this->setData(self::PRODUCT_IMAGE_CONTAINER, $productImageContainer);
    }

    public function getProductImageContainer()
    {
        return $this->getData(self::PRODUCT_IMAGE_CONTAINER);
    }

    public function setProductImageWidth($productImageWidth)
    {
        $this->setData(self::PRODUCT_IMAGE_WIDTH, $productImageWidth);
    }

    public function getProductImageWidth()
    {
        return $this->getData(self::PRODUCT_IMAGE_WIDTH);
    }

    public function setProductImageHeight($productImageHeight)
    {
        $this->setData(self::PRODUCT_IMAGE_HEIGHT, $productImageHeight);
    }

    public function getProductImageHeight()
    {
        return $this->getData(self::PRODUCT_IMAGE_HEIGHT);
    }

    public function setProductImageCss($productImageCss)
    {
        $this->setData(self::PRODUCT_IMAGE_CSS, $productImageCss);
    }

    public function getProductImageCss()
    {
        return $this->getData(self::PRODUCT_IMAGE_CSS);
    }

    public function setProductTextBackground($productTextBackground)
    {
        $this->setData(self::PRODUCT_TEXT_BACKGROUND, $productTextBackground);
    }

    public function getProductTextBackground()
    {
        return $this->getData(self::PRODUCT_TEXT_BACKGROUND);
    }

    public function setProductTextComment($productTextComment)
    {
        $this->setData(self::PRODUCT_TEXT_COMMENT, $productTextComment);
    }

    public function getProductTextComment()
    {
        return $this->getData(self::PRODUCT_TEXT_COMMENT);
    }

    public function setProductTextLabelPosition($productTextLabelPosition)
    {
        $this->setData(self::PRODUCT_TEXT_LABEL_POSITION, $productTextLabelPosition);
    }

    public function getProductTextLabelPosition()
    {
        return $this->getData(self::PRODUCT_TEXT_LABEL_POSITION);
    }

    public function setProductTextFontsize($productTextFontsize)
    {
        $this->setData(self::PRODUCT_TEXT_FONTSIZE, $productTextFontsize);
    }

    public function getProductTextFontsize()
    {
        return $this->getData(self::PRODUCT_TEXT_FONTSIZE);
    }

    public function setProductTextFontcolor($productTextFontcolor)
    {
        $this->setData(self::PRODUCT_TEXT_FONTCOLOR, $productTextFontcolor);
    }

    public function getProductTextFontcolor()
    {
        return $this->getData(self::PRODUCT_TEXT_FONTCOLOR);
    }

    public function setProductTextPosition($productTextPosition)
    {
        $this->setData(self::PRODUCT_TEXT_POSITION, $productTextPosition);
    }

    public function getProductTextPosition()
    {
        return $this->getData(self::PRODUCT_TEXT_POSITION);
    }

    public function setProductTextContainer($productTextContainer)
    {
        $this->setData(self::PRODUCT_TEXT_CONTAINER, $productTextContainer);
    }

    public function getProductTextContainer()
    {
        return $this->getData(self::PRODUCT_TEXT_CONTAINER);
    }

    public function setProductTextWidth($productTextWidth)
    {
        $this->setData(self::PRODUCT_TEXT_WIDTH, $productTextWidth);
    }

    public function getProductTextWidth()
    {
        return $this->getData(self::PRODUCT_TEXT_WIDTH);
    }

    public function setProductTextHeight($productTextHeight)
    {
        $this->setData(self::PRODUCT_TEXT_HEIGHT, $productTextHeight);
    }

    public function getProductTextHeight()
    {
        return $this->getData(self::PRODUCT_TEXT_HEIGHT);
    }

    public function setProductTextCss($productTextCss)
    {
        $this->setData(self::PRODUCT_TEXT_CSS, $productTextCss);
    }

    public function getProductTextCss()
    {
        return $this->getData(self::PRODUCT_TEXT_CSS);
    }

    public function getCategoryLabelStatus()
    {
        return $this->getData(self::CATEGORY_LABEL_STATUS);
    }

    public function getCategoryLabelType()
    {
        return $this->getData(self::CATEGORY_LABEL_TYPE);
    }

    public function setCategoryImageLabel($categoryImageLabel)
    {
        $this->setData(self::CATEGORY_IMAGE_LABEL, $categoryImageLabel);
    }

    public function getCategoryImageLabel()
    {
        return $this->getData(self::CATEGORY_IMAGE_LABEL);
    }

    public function setCategoryImagePosition($categoryImagePosition)
    {
        $this->setData(self::CATEGORY_IMAGE_POSITION, $categoryImagePosition);
    }

    public function getCategoryImagePosition()
    {
        return $this->getData(self::CATEGORY_IMAGE_POSITION);
    }

    public function setCategoryImageContainer($categoryImageContainer)
    {
        $this->setData(self::CATEGORY_IMAGE_CONTAINER, $categoryImageContainer);
    }

    public function getCategoryImageContainer()
    {
        return $this->getData(self::CATEGORY_IMAGE_CONTAINER);
    }

    public function setCategoryImageWidth($categoryImageWidth)
    {
        $this->setData(self::CATEGORY_IMAGE_WIDTH, $categoryImageWidth);
    }

    public function getCategoryImageWidth()
    {
        return $this->getData(self::CATEGORY_IMAGE_WIDTH);
    }

    public function setCategoryImageHeight($categoryImageHeight)
    {
        $this->setData(self::CATEGORY_IMAGE_HEIGHT, $categoryImageHeight);
    }

    public function getCategoryImageHeight()
    {
        return $this->getData(self::CATEGORY_IMAGE_HEIGHT);
    }

    public function setCategoryImageCss($categoryImageCss)
    {
        $this->setData(self::CATEGORY_IMAGE_CSS, $categoryImageCss);
    }

    public function getCategoryImageCss()
    {
        return $this->getData(self::CATEGORY_IMAGE_CSS);
    }

    public function setCategoryTextBackground($categoryTextBackground)
    {
        $this->setData(self::CATEGORY_TEXT_BACKGROUND, $categoryTextBackground);
    }

    public function getCategoryTextBackground()
    {
        return $this->getData(self::CATEGORY_TEXT_BACKGROUND);
    }

    public function setCategoryTextComment($categoryTextComment)
    {
        $this->setData(self::CATEGORY_TEXT_COMMENT, $categoryTextComment);
    }

    public function getCategoryTextComment()
    {
        return $this->getData(self::CATEGORY_TEXT_COMMENT);
    }

    public function setCategoryTextLabelPosition($categoryTextLabelPosition)
    {
        $this->setData(self::CATEGORY_TEXT_LABEL_POSITION, $categoryTextLabelPosition);
    }

    public function getCategoryTextLabelPosition()
    {
        return $this->getData(self::CATEGORY_TEXT_LABEL_POSITION);
    }

    public function setCategoryTextFontsize($categoryTextFontsize)
    {
        $this->setData(self::CATEGORY_TEXT_FONTSIZE, $categoryTextFontsize);
    }

    public function getCategoryTextFontsize()
    {
        return $this->getData(self::CATEGORY_TEXT_FONTSIZE);
    }

    public function setCategoryTextFontcolor($categoryTextFontcolor)
    {
        $this->setData(self::CATEGORY_TEXT_FONTCOLOR, $categoryTextFontcolor);
    }

    public function getCategoryTextFontcolor()
    {
        return $this->getData(self::CATEGORY_TEXT_FONTCOLOR);
    }

    public function setCategoryTextPosition($categoryTextPosition)
    {
        $this->setData(self::CATEGORY_TEXT_POSITION, $categoryTextPosition);
    }

    public function getCategoryTextPosition()
    {
        return $this->getData(self::CATEGORY_TEXT_POSITION);
    }

    public function setCategoryTextContainer($categoryTextContainer)
    {
        $this->setData(self::CATEGORY_TEXT_CONTAINER, $categoryTextContainer);
    }

    public function getCategoryTextContainer()
    {
        return $this->getData(self::CATEGORY_TEXT_CONTAINER);
    }

    public function setCategoryTextWidth($categoryTextWidth)
    {
        $this->setData(self::CATEGORY_TEXT_WIDTH, $categoryTextWidth);
    }

    public function getCategoryTextWidth()
    {
        return $this->getData(self::CATEGORY_TEXT_WIDTH);
    }

    public function setCategoryTextHeight($categoryTextHeight)
    {
        $this->setData(self::CATEGORY_TEXT_HEIGHT, $categoryTextHeight);
    }

    public function getCategoryTextHeight()
    {
        return $this->getData(self::CATEGORY_TEXT_HEIGHT);
    }

    public function setCategoryTextCss($categoryTextCss)
    {
        $this->setData(self::CATEGORY_TEXT_CSS, $categoryTextCss);
    }

    public function getCategoryTextCss()
    {
        return $this->getData(self::CATEGORY_TEXT_CSS);
    }

    public function setConditionsSerialized($conditionsSerialized)
    {
        $this->setData(self::CONDITIONS_SERIALIZED, $conditionsSerialized);
    }

    public function getConditionsSerialized()
    {
        return $this->getData(self::CONDITIONS_SERIALIZED);
    }

    public function setUseDateRange($useDateRange)
    {
        $this->setData(self::USE_DATE_RANGE, $useDateRange);
    }

    public function getUseDateRange()
    {
        return $this->getData(self::USE_DATE_RANGE);
    }

    public function setFromDate($fromDate)
    {
        $this->setData(self::FROM_DATE, $fromDate);
    }

    public function getFromDate()
    {
        return $this->getData(self::FROM_DATE);
    }

    public function setFromTime($fromTime)
    {
        $this->setData(self::FROM_TIME, $fromTime);
    }

    public function getFromTime()
    {
        return $this->getData(self::FROM_TIME);
    }

    public function setToDate($toDate)
    {
        $this->setData(self::TO_DATE, $toDate);
    }

    public function getToDate()
    {
        return $this->getData(self::TO_DATE);
    }

    public function setToTime($fromTime)
    {
        $this->setData(self::TO_TIME, $fromTime);
    }

    public function getToTime()
    {
        return $this->getData(self::TO_TIME);
    }

    public function setIsNew($isNew)
    {
        $this->setData(self::IS_NEW, $isNew);
    }

    public function getIsNew()
    {
        return $this->getData(self::IS_NEW);
    }

    public function setIsOnSale($isOnSale)
    {
        $this->setData(self::IS_ON_SALE, $isOnSale);
    }

    public function getIsOnSale()
    {
        return $this->getData(self::IS_ON_SALE);
    }

    public function setStockStatus($stockStatus)
    {
        $this->setData(self::STOCK_STATUS, $stockStatus);
    }

    public function getStockStatus()
    {
        return $this->getData(self::STOCK_STATUS);
    }

    public function setUsePriceRange($usePriceRange)
    {
        $this->setData(self::USE_PRICE_RANGE, $usePriceRange);
    }

    public function getUsePriceRange()
    {
        return $this->getData(self::USE_PRICE_RANGE);
    }

    public function setByPrice($byPrice)
    {
        $this->setData(self::BY_PRICE, $byPrice);
    }

    public function getByPrice()
    {
        return $this->getData(self::BY_PRICE);
    }

    public function setFromPrice($fromPrice)
    {
        $this->setData(self::FROM_PRICE, $fromPrice);
    }

    public function getFromPrice()
    {
        return $this->getData(self::FROM_PRICE);
    }

    public function setToPrice($toPrice)
    {
        $this->setData(self::TO_PRICE, $toPrice);
    }

    public function getToPrice()
    {
        return $this->getData(self::TO_PRICE);
    }

    public function setUseCustomerGroup($useCustomerGroup)
    {
        $this->setData(self::USE_CUSTOMER_GROUP, $useCustomerGroup);
    }

    public function getUseCustomerGroup()
    {
        return $this->getData(self::USE_CUSTOMER_GROUP);
    }

    public function setCustomerGroupIds($customerGroupIds)
    {
        $this->setData(self::CUSTOMER_GROUP_IDS, $customerGroupIds);
    }

    public function getCustomerGroupIds()
    {
        return $this->getData(self::CUSTOMER_GROUP_IDS);
    }

    /**
     * Getter for rule conditions collection
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
    public function getConditionsInstance()
    {
        return $this->_combineFactory->create();
    }

    /**
     * Getter for rule actions collection
     *
     * @return \Magento\CatalogRule\Model\Rule\Action\Collection
     */
    public function getActionsInstance()
    {
        return $this->_actionCollectionFactory->create();
    }

    /**
     * @return array
     */
    protected function _getAllStoreIds()
    {
        return array_keys($this->_storeManager->getStores());
    }
}
