<?php
/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Controller\Adminhtml\Productlabel;

use Magento\Backend\App\Action;
use TemplateMonster\ProductLabels\Api\ProductLabelRepositoryInterfaceFactory;

class Delete extends Action
{
    /**
     * @var \TemplateMonster\ProductLabels\Api\ProductLabelRepositoryInterface
     */
    protected $_productLabelRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        ProductLabelRepositoryInterfaceFactory $productLabelRepository,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->_productLabelRepository = $productLabelRepository;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_ProductLabels::productlabels_delete');
    }

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('smart_label_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $smartLabelRepository = $this->_productLabelRepository->create();

        if ($id) {
            $title = "";
            try {
                $smartLabelRepository->deleteById($id);
                // display success message
                $this->messageManager->addSuccess(__('The smart label has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_smart_label_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/index/index');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_smart_label_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['smart_label_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a smart label to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
