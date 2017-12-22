<?php
namespace Jjxuhuade\Smtp\Model\ResourceModel;



use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Log extends AbstractDb
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\Model\ResourceModel\AbstractResource::_construct()
     */
    protected function _construct()
    {
        // TODO Auto-generated method stub
        $this->_init('smtppro_email_log', 'email_id');
        
    }


 
}

