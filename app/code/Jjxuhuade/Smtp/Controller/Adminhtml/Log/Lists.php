<?php
namespace Jjxuhuade\Smtp\Controller\Adminhtml\Log;

use Magento\Backend\App\Action;

class Lists  extends Action
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $resultPage=$this->resultFactory->create('page');
        $resultPage->setActiveMenu('Jjxuhuade_Smtp::log');
        $resultPage->addBreadcrumb(__('Smtp'), __('Smtp'));
        $resultPage->addBreadcrumb(__('Smtp'), __('Smtp'));
        $resultPage->getConfig()->getTitle()->prepend(__('Smtp Log List'));
        return $resultPage;
        
    }

    
}

