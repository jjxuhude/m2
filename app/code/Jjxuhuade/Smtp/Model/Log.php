<?php
namespace Jjxuhuade\Smtp\Model;

use Magento\Sales\Model\AbstractModel;

class Log extends AbstractModel
{
    
    protected $_idFieldName = 'email_id';
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Jjxuhuade\Smtp\Model\ResourceModel\Log');
    }
    
    public function validate()
    {
        return true;
    }
}

