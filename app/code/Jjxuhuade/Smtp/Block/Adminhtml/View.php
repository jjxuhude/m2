<?php
namespace Jjxuhuade\Smtp\Block\Adminhtml;

use Magento\Framework\App\ObjectManager;

class View extends  \Magento\Backend\Block\Template
{
    protected $_template="Jjxuhuade_Smtp::view.phtml";

    function getLog(){
        $id=$this->getRequest()->getParam('email_id');
        $log=$this->getRequest()->getObjectManager()->get('Jjxuhuade\Smtp\Model\Log')->load($id);
        return $log;
    }
    
}

