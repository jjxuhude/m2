<?php
namespace TemplateMonster\ThemeUpdater\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class UpdateWarning extends Field
{
    public function __construct(
        Context $context,
        array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $html = $this->_renderValue($element);
        return $this->_decorateRowHtml($element, $html);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _renderValue(AbstractElement $element)
    {
        $html = '<div class="messages">';
        if ($element->getComment()) {
            $html = '<div class="message message-warning warning"><div data-ui-id="messages-message-success">' .
                $element->getComment() . '</div></div>';
        }
        $html .= '';

        return $html;
    }

    /**
     * @param AbstractElement $element
     * @param string $html
     * @return string
     */
    protected function _decorateRowHtml(AbstractElement $element, $html)
    {
        return $html;
    }
}