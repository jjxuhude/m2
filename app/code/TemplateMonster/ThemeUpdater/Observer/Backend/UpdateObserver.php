<?php
namespace TemplateMonster\ThemeUpdater\Observer\Backend;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use TemplateMonster\ThemeUpdater\Model\UpdateData;
use \Magento\AdminNotification\Model\InboxFactory;

class UpdateObserver implements ObserverInterface
{
    /**
     * @var UpdateData
     */
    protected $_updateData;

    /**
     * @var InboxFactory
     */
    protected $_inboxFactory;

    /**
     * @var MessageManager
     */
    protected $_messageManager;

    public function __construct(
        UpdateData $updateData,
        InboxFactory $inboxFactory
    )
    {
        $this->_updateData = $updateData;
        $this->_inboxFactory = $inboxFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if($observer->getRequest()->isAjax()){
            return;
        }

        if($observer->getRequest()->getControllerName() == 'system_store'){
            return;
        }

        if(!$this->_updateData->isUpdateRequired()){
            return;
        }

        if ($this->_updateData->getFrequency() + $this->_updateData->getLastUpdate() > time()) {
            return;
        }

        $this->getUpdateMessage();
        $this->_updateData->setLastUpdate();

        return;
    }

    /**
     * Get update notification message
     *
     * @return $this
     */
    protected function getUpdateMessage()
    {
        $messageTitle = __('Theme update available.');
        $messageBody = __('New version of the installed theme is available. You can get update files from the
        "Stores > Configuration > TemplateMonster > Theme Updater" section. ');

        $this->_inboxFactory->create()->addNotice($messageTitle->getText(), $messageBody->getText());
        return $this;
    }
}