<?php
namespace TemplateMonster\ThemeUpdater\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use TemplateMonster\ThemeUpdater\Helper\Data as HelperData;


class CheckUpdates extends Field
{
    protected $_template = 'TemplateMonster_ThemeUpdater::system/config/field/check_updates.phtml';

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    public function __construct(
        Context $context,
        HelperData $helperData,
        array $data = [])
    {
        $this->_helperData = $helperData;
        $this->_request = $context->getRequest();

        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return parent::render($element);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * @return string
     */
    public function getUpdateUrl()
    {
        $result = $this->getUrl('theme_updater/system_config/checkupdates');
        return $result;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonHtml()
    {
        $disabled = !$this->_helperData->isConfigSet();

        $button = $this->getLayout()
            ->createBlock('Magento\Backend\Block\Widget\Button')
            ->setData(
            [
                'id' => 'check_updates',
                'label' => __('Check Updates'),
                'disabled' => $disabled
            ]
        );

        return $button->toHtml();
    }

}