<?php
namespace Jjxuhuade\Smtp\Controller\Adminhtml\Log;

use Magento\Backend\App\Action;

class Preview extends Action
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $id=$this->getRequest()->getParam('email_id');
        $log=$this->_objectManager->get('Jjxuhuade\Smtp\Model\Log')->load($id);
        echo $log->getData('email_body');
        exit;
    }

}

