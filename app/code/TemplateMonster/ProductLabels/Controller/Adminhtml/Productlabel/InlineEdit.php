<?php
/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Controller\Adminhtml\Productlabel;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use TemplateMonster\ProductLabels\Api\ProductLabelRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class InlineEdit extends Action
{

    /**
     * @var ProductLabelRepositoryInterface
     */
    protected $productLabelRepository;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param ProductLabel
     * ProductLabelRepositoryInterface $productLabelRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        ProductLabelRepositoryInterface $productLabelRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->productLabelRepository = $productLabelRepository;
        $this->jsonFactory = $jsonFactory;
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_ProductLabels::productlabels_mass');
    }


    /**
     * @return $this
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $productLabelId) {
            $productLabel = $this->productLabelRepository->getById($productLabelId);
            try {
                $productLabelData = $postItems[$productLabelId];
                $productLabel->setData($productLabelData);
                $this->productLabelRepository->save($productLabel);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithProductLabel($productLabel, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithProductLabel($productLabel, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithProductLabel(
                    $productLabel,
                    __('Something went wrong while saving the Product Label.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * @param $productLabel
     * @param $errorText
     * @return string
     */
    protected function getErrorWithProductLabel($productLabel, $errorText)
    {
        return '[ProductLabel ID: ' . $productLabel->getId() . '] ' . $errorText;
    }
}
