<?php
namespace TemplateMonster\ThemeUpdater\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use TemplateMonster\ThemeUpdater\Model\UpdateDataFactory;
use TemplateMonster\ThemeUpdater\Helper\Data as HelperData;

class CheckUpdates extends Action
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var UpdateDataFactory
     */
    protected $_updateDataFactory;

    /**
     * @var HelperData
     */
    protected $_helperData;

    /**
     * @var \TemplateMonster\ThemeUpdater\Model\UpdateData
     */
    protected $_updateData;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        UpdateDataFactory $updateDataFactory,
        HelperData $helperData
    )
    {
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_updateDataFactory = $updateDataFactory;
        $this->_helperData = $helperData;

        return parent::__construct($context);
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();

        $this->_updateData = $this->_updateDataFactory->create()->setData($this->_getUpdateRequestParams());

        return $result->setData([
                'update_required' => $this->_updateData->isUpdateRequired(),
                'download_url' => $this->_updateData->getDownloadUrl(),
            ]);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TemplateMonster_ThemeUpdater::system_config');
    }

    /**
     * @return $this
     */
    protected function _getUpdateRequestParams()
    {
        $result = array(
                'itemID' => $this->_helperData->getTemplateId(),
                'orderID' => $this->_helperData->getOrderId(),
                'version' => $this->_helperData->getVersion()
        );

        return $result;
    }
}