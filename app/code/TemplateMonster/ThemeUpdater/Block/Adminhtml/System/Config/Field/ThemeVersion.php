<?php
namespace TemplateMonster\ThemeUpdater\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use \Magento\Framework\Data\Form\Element\AbstractElement;
use TemplateMonster\ThemeUpdater\Model\ThemeData;

class ThemeVersion extends Field
{
    /**
     * @var ThemeData
     */
    protected $_themeData;

    public function __construct(
        Context $context,
        ThemeData $themeData,
        array $data = [])
    {
        $this->_themeData = $themeData;
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $version = $this->_themeData->getVersion();
        $element->setValue($version);
        $element->setData('disabled', 'disabled');

        return parent::_getElementHtml($element);
    }
}