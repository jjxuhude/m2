<?php
namespace TemplateMonster\ThemeUpdater\Block\Adminhtml\System\Config\Field;

class Backup extends CheckUpdates
{
    protected $_template = 'TemplateMonster_ThemeUpdater::system/config/field/backup.phtml';

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
                'id' => 'backup',
                'label' => __('Backup'),
            ]
        );

        return $button->toHtml();
    }

    /**
     * @return string
     */
    public function getBackupUrl()
    {
        return $this->getUrl('theme_updater/system_config/backup', $this->_request->getParams());
    }
}