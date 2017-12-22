<?php
namespace TemplateMonster\ThemeUpdater\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Webapi\Exception;
use TemplateMonster\ThemeUpdater\Model\BackupFactory;
use TemplateMonster\ThemeUpdater\Model\ThemeDataFactory;
use TemplateMonster\ThemeUpdater\Helper\Data as HelperData;

class Backup extends Action
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var \TemplateMonster\ThemeUpdater\Model\Backup
     */
    protected $_backup;

    /**
     * @var \TemplateMonster\ThemeUpdater\Model\ThemeData
     */
    protected $_themeData;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var ThemeDataFactory
     */
    protected $_themeDataFactory;

    /**
     * @var BackupFactory
     */
    protected $_backupFactory;

    /**
     * Backup constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param BackupFactory $backupFactory
     * @param ThemeDataFactory $themeDataFactory
     * @param HelperData $helperData
     */

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        BackupFactory $backupFactory,
        ThemeDataFactory $themeDataFactory,
        HelperData $helperData
    )
    {
        parent::__construct($context);

        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_helperData = $helperData;
        $this->_backupFactory = $backupFactory;
        $this->_themeDataFactory = $themeDataFactory;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();

        $this->_themeData = $this->_themeDataFactory->create();
        $this->_backup = $this->_backupFactory->create();

        try{
            $this->_backup->createBackup();
        } catch(\Magento\Framework\Exception\LocalizedException $e) {
            $this->_backup->setData('message', $e->getMessage());
            $this->_backup->setData('status', false);
        }

        return $result->setData([
                'filepath' => $this->_backup->getData('filepath'),
                'status' => $this->_backup->getData('status'),
                'message' => $this->_backup->getData('message'),
            ]);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_ThemeUpdater::system_config');
    }
}