<?php
namespace Jjxuhuade\Smtp\Controller\Adminhtml\Log;

use Magento\Backend\App\Action;

class View extends Action
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $resultPage=$this->resultFactory->create('page');
        $resultPage->getConfig()->getTitle()->prepend(__('Smtp Log View'));
        $resultPage->addContent($resultPage->getLayout()->createBlock('Jjxuhuade\Smtp\Block\Adminhtml\View'));
        
        return $resultPage;
    }

}

