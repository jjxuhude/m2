<?php

namespace TemplateMonster\QuickView\Controller\Preview;

use TemplateMonster\QuickView\Helper\Data as QuickViewHelper;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    /**
     * @var QuickViewHelper
     */
    protected $_quickViewHelper;

    public function __construct(
        QuickViewHelper $helper,
        Context $context
    ) {
        $this->_quickViewHelper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData([
            'status' => 'success',
            'content' => 'Ololo trololo',
        ]);

        return $result;
    }
}
