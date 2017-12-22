<?php

namespace TemplateMonster\QuickView\Block\Button;

use TemplateMonster\QuickView\Block\AbstractBlock;
use TemplateMonster\QuickView\Helper\Data as QuickViewHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\View\Element\Template;

/**
 * Class Init.
 */
class Init extends AbstractBlock
{
    /**
     * @var JsonHelper
     */
    protected $_jsonHelper;

    /**
     * @var string
     */
    protected $_template = 'button/init.phtml';

    /**
     * Init constructor.
     *
     * @param JsonHelper       $jsonHelper
     * @param QuickViewHelper  $quickViewHelper
     * @param Template\Context $context
     * @param array            $data]
     */
    public function __construct(
        JsonHelper $jsonHelper,
        QuickViewHelper $quickViewHelper,
        Template\Context $context,
        array $data = []
    ) {
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($quickViewHelper, $context, $data);
    }

    /**
     * Get JSON configuration options.
     *
     * @return string
     */
    public function getJsonConfigurationOptions()
    {
        return $this->_jsonHelper->jsonEncode(
            $this->getConfigurationOptions()
        );
    }

    /**
     * Get configuration options.
     *
     * @return array
     */
    public function getConfigurationOptions()
    {
        return [
            'buttonText' => __($this->_quickViewHelper->getButtonText()),
            'buttonCssClass' => $this->_quickViewHelper->getButtonCssClass(),
        ];
    }
}
