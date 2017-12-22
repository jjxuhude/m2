<?php
/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Model\Filter;

class Price
{

    /**
     * @var array
     */
    protected $_allowedPriceType = ['price','special_price'];

    /**
     * @param $collection
     * @param $type
     * @param $fromPrice
     * @param $toPrice
     * @throws \Exception
     */
    public function addPriceFilter($collection, $type, $fromPrice, $toPrice)
    {
        if (in_array($type, $this->_allowedPriceType)) {
            if ($fromPrice) {
                $collection->addAttributeToFilter($type, ['gteq'=>$fromPrice]);
            }
            if ($toPrice) {
                $collection->addAttributeToFilter($type, ['lteq'=>$toPrice]);
            }
        } elseif ($type == 'final_price') {
            if ($fromPrice) {
                $collection->getSelect()->where('price_index.final_price >= ?', $fromPrice);
            }
            if ($toPrice) {
                $collection->getSelect()->where('price_index.final_price <= ?', $toPrice);
            }
        } else {
            throw new \Exception('Current price type do not support.');
        }
    }
}
