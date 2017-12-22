<?php
namespace TemplateMonster\ThemeUpdater\Block\Adminhtml\System\Config\Field;

class Download extends CheckUpdates
{
    protected $_template = 'TemplateMonster_ThemeUpdater::system/config/field/download.phtml';

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()
            ->createBlock('Magento\Backend\Block\Widget\Button')
            ->setData(
            [
                'id' => 'download',
                'label' => __('Download'),
                'disabled' => true
            ]
        );

        return $button->toHtml();
    }
}