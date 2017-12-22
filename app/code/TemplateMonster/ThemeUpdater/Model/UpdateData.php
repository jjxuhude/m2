<?php

namespace TemplateMonster\ThemeUpdater\Model;

use Magento\Framework\HTTP\Client\Curl;
use TemplateMonster\ThemeUpdater\Helper\Data as HelperData;
use \Magento\Backend\App\ConfigInterface;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\Model\ResourceModel\AbstractResource;
use \Magento\Framework\Data\Collection\AbstractDb;

class UpdateData extends \Magento\Framework\Model\AbstractModel
{
    const UPDATE_URL = 'http://updates.templatemonster.com/update/';
//    const XML_FREQUENCY_PATH = 'system/adminnotification/frequency';
    const CACHE_KEY = 'theme_updater_notifications_lastcheck';

    protected $_frequency = 3600;

    /**
     * @var Curl
     */
    protected $_curl;

    /**
     * @var \Magento\Backend\App\ConfigInterface
     */
    protected $_backendConfig;

    /**
     * @var HelperData
     */
    protected $_helperData;

    public function __construct(
        Curl $curl,
        HelperData $helperData,
        ConfigInterface $backendConfig,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ){
        $this->_curl = $curl;
        $this->_helperData = $helperData;
        $this->_backendConfig = $backendConfig;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Get available releases for selected Item
     *
     * @return string
     */
    public function getAvailableReleases()
    {
        $itemId = $this->_helperData->getTemplateId();

        if($itemId == null){
            return false;
        }

        $url = self::UPDATE_URL . $itemId . '/release';

        $result = $this->getApiData($url);
        return $result;
    }

    /**
     * Get recent release version
     *
     * @return int
     */
    public function getRecentVersion()
    {
        if(!$this->getAvailableReleases()){
            return false;
        }

        $releasesArray = json_decode($this->getAvailableReleases())->content;
        $recentVersion = $releasesArray[count($releasesArray) - 1]->version;

        return $recentVersion;
    }

    /**
     * Get item release info
     *
     * @return string
     */
    public function getReleaseInfo()
    {
        $query = http_build_query(
            array(
                'orderID' => $this->getData('orderID'),
                'license' => $this->generateLicenseKey()
            ));

        $url = self::UPDATE_URL . $this->getData('itemID') . '/release/' . $this->getRecentVersion() . '/link?' .
            $query;

        return $this->getApiData($url);
    }

    /**
     * @return bool
     */
    public function isUpdateRequired()
    {
        $currentVersion = $this->_helperData->getVersion();
        $recentVersion = $this->getRecentVersion();

        $updateRequired = version_compare($recentVersion, $currentVersion) > 0;

        return $updateRequired;
    }

    /**
     * Get data from TM Api
     *
     * @param $url
     * @return string
     */
    protected function getApiData($url)
    {
        $ch = $this->_curl;
        $ch->setHeaders(array("Content-Type: application/json"));
        $ch->get($url);

        return $ch->getBody();
    }

    /**
     * Generate license key for release info request
     *
     * @return string
     */
    protected function generateLicenseKey()
    {
        $data = strtoupper ($this->getData('orderID') . $this->getData('itemID'));
        return hash('sha256', $data);
    }

    /**
     * Get update download url
     *
     * @return mixed
     */
    public function getDownloadUrl()
    {
        if(!$this->isUpdateRequired()){
            return false;
        }

        $releaseInfo = json_decode($this->getReleaseInfo());

        if(isset($releaseInfo->content)){
            $result = $releaseInfo->content->downloadLink;
        } else {
            $result = $releaseInfo->errorMessage;
        }

        return $result;
    }

    /**
     * Retrieve Update Frequency
     *
     * @return int
     */
    public function getFrequency()
    {
//        $this->_backendConfig->getValue(self::XML_FREQUENCY_PATH);
        return $this->_frequency;
    }

    /**
     * Retrieve Last update time
     *
     * @return int
     */
    public function getLastUpdate()
    {
        return $this->_cacheManager->load(self::CACHE_KEY);
    }

    /**
     * Set last update time (now)
     *
     * @return $this
     */
    public function setLastUpdate()
    {
        $this->_cacheManager->save(time(), self::CACHE_KEY);
        return $this;
    }

}