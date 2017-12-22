<?php
namespace Jjxuhuade\Smtp\Model\ResourceModel\Log;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection::_construct()
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Jjxuhuade\Smtp\Model\Log', 'Jjxuhuade\Smtp\Model\ResourceModel\Log');
        
    }

    
}

