<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\AjaxSearch\Model\Autocomplete\Product;

use Magento\Search\Model\QueryFactory;
use Magento\Search\Model\Autocomplete\ItemFactory;
use Magento\Search\Model\Autocomplete\DataProviderInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Helper\Image;
use TemplateMonster\AjaxSearch\Helper\AjaxSearch;

class DataProvider implements DataProviderInterface
{
    /**
     * Query factory
     *
     * @var QueryFactory
     */
    protected $queryFactory;

    /**
     * Autocomplete result item factory
     *
     * @var ItemFactory
     */
    protected $itemFactory;

    /**
     * @var AjaxSearch
     */
    protected $_helper;

    /**
     * @param QueryFactory $queryFactory
     * @param ItemFactory $itemFactory
     */
    public function __construct(
        QueryFactory $queryFactory,
        ItemFactory $itemFactory,
        CollectionFactory $productCollection,
        Image $imageHelper,
        AjaxSearch $helper
    ) {
        $this->queryFactory = $queryFactory;
        $this->itemFactory = $itemFactory;
        $this->_productCollection = $productCollection;
        $this->_imageHelper = $imageHelper;
        $this->_helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $productSearchStatus = $this->_helper->getProductSearchStatus();
        $productSearchNumResult = $this->_helper->getProductSearchNumResult();

        if (!$productSearchStatus || ($productSearchNumResult <= 0)) {
            return [];
        }

        $query = $this->queryFactory->get()->getQueryText();
        $productCollection = $this->_productCollection->create();
        $productCollection->addFieldToSelect(['name', 'thumbnail', 'price', 'special_price']);
        $productCollection->addFieldToFilter('name', ['like'=>'%'.$query.'%']);
        $productCollection->addFieldToFilter('visibility',
            ['in'=>[Visibility::VISIBILITY_BOTH, Visibility::VISIBILITY_IN_CATALOG, Visibility::VISIBILITY_IN_SEARCH]]
        );
        $productCollection->setPageSize($productSearchNumResult);
        $productCollection->setCurPage(1);

        $result = [];
        foreach ($productCollection->getItems() as $product) {
            if ($product->getThumbnail()) {
                $img = $this->_imageHelper->init($product, 'product_page_image_small')
                    ->setImageFile($product->getThumbnail())
                    ->getUrl();
            }

            $resultItem = $this->itemFactory->create([
                'title' => $product->getName(),
                'url'=>$product->getProductUrl(),
                'image'=>$img,
                'price'=>$product->getFormatedPrice(),
                'product'=>true
            ]);
            if ($resultItem->getTitle() == $query) {
                array_unshift($result, $resultItem);
            } else {
                $result[] = $resultItem;
            }
        }

        return $result;
    }
}
