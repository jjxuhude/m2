<?php

namespace TemplateMonster\QuickView\Block;

use TemplateMonster\QuickView\Helper\Data as QuickViewHelper;
use Magento\Framework\View\Element\Template;

/**
 * Class AbstractBlock.
 */
abstract class AbstractBlock extends Template
{
    /**
     * @var QuickViewHelper
     */
    protected $_quickViewHelper;

    /**
     * Init constructor.
     *
     * @param QuickViewHelper  $quickViewHelper
     * @param Template\Context $context
     * @param array            $data
     */
    public function __construct(
        QuickViewHelper $quickViewHelper,
        Template\Context $context,
        array $data = []
    ) {
        $this->_quickViewHelper = $quickViewHelper;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if (!$this->_quickViewHelper->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }
}
