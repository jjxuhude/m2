<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Api;

interface ProductLabelRepositoryInterface
{

    /**
     * @param \TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface $productLabel
     * @return \TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface $productLabel);

    /**
     * @param $productLabelId
     * @return \TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($productLabelId);

    /**
     * @param \TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface $productLabel
     * @return \TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface $productLabel);

    /**
     * @param $productLabelId
     * @return \TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($productLabelId);

    /**
     * @return \TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getModelInstance();
}
