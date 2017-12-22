<?php
namespace TemplateMonster\CatalogImagesGrid\Observer;

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

use Magento\Framework\Event\ObserverInterface;

class CatalogCategoryPrepareSaveObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $category = $observer->getCategory();

        $thumbnail = $category->getThumbnail();
        if(!$thumbnail) {
            $category->setThumbnail(['delete' => true]);
        }
        $category->setThumbnail($this->_filterCategoryPostData($thumbnail));
    }

    protected function _filterCategoryPostData($thumbnial)
    {
        $data = $thumbnial;
        // @todo It is a workaround to prevent saving this data in category model and it has to be refactored in future
        if (isset($data) && is_array($data)) {
            if (!empty($data['delete'])) {
                $data = null;
            } else {
                if (isset($data[0]['name']) && isset($data[0]['tmp_name'])) {
                    $data = $data[0]['name'];
                } else {
                    unset($data['image']);
                }
            }
        }
        return $data;
    }

}