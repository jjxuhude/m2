<?php
namespace Jjxuhuade\Smtp\Block\Adminhtml;



use Magento\Backend\Block\Widget\Grid\Container;

class Log extends Container
{
    protected function _construct()
    {
        /**
         * 自定义grid时需要提供
         */
        //$this->_blockGroup = 'Jjxuhuade_Smtp'; //模块
        //$this->_controller = 'adminhtml_log';  //block目录下的子路径
        //$this->_headerText = __('Smtp Log Lists');
        $this->_addButtonLabel = null ;
        parent::_construct();
    }
}

