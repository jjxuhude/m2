<?php
namespace Jjxuhuade\Smtp\Model\Config;

use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\App\ObjectManager;

class Connection implements ArrayInterface
{

    public function toOptionArray()
    {
        $items=$this->toArray();
        array_walk($items, function (&$val,$key){
            $val= ['value'=>$key,'label'=>$val];
        });
            
        return array_values($items);
  
    }
    
    public function toArray()
    {
        return ObjectManager::getInstance()->get('Magento\Framework\App\Config')->getValue('jjxuhuade_smtp/configuration/types');
    }
}

