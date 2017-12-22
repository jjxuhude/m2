<?php
namespace TemplateMonster\ThemeUpdater\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Theme\Model\Theme;
use \Magento\Store\Model\ScopeInterface as Scope;
use TemplateMonster\ThemeUpdater\Model\ThemeData;

class Data extends AbstractHelper
{
    const GENERAL_TEMPLATE_ID = 'themeupdater/general/template_id';
    const GENERAL_ORDER_ID = 'themeupdater/general/order_id';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Theme
     */
    protected $_theme;

    /**
     * @var ThemeData
     */
    protected $_themeData;

    public function __construct(
        Context $context,
        Theme $theme,
        ThemeData $themeData
    ) {
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_theme = $theme;
        $this->_themeData = $themeData;
        parent::__construct($context);
    }

    /**
     * @param $path
     * @param $scope
     * @return mixed
     */
    protected function getConfigValue($path, $scope)
    {
        return $this->_scopeConfig->getValue($path, $scope);
    }

    /**
     * @return array
     */
    public function getTmThemes()
    {
        return $this->_theme->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('theme_path', array('like' => '%TemplateMonster_%'))
            ->getData();
    }

    /**
     * @return mixed
     */
    public function getTemplateId()
    {
        return $this->getConfigValue(self::GENERAL_TEMPLATE_ID, Scope::SCOPE_STORE );
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->getConfigValue(self::GENERAL_ORDER_ID, Scope::SCOPE_STORE );
    }

    /**
     * Check if all config set
     *
     * @return bool
     */
    public function isConfigSet()
    {
        $templateId = $this->getTemplateId();
        $orderId = $this->getOrderId();
        $version = $this->_themeData->getVersion();

        return isset($templateId, $orderId, $version);
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->_themeData->getVersion();
    }

    /**
     * Get current scope(store, website) and it's id
     *
     * @return array
     */
    public function getScopeParams()
    {
        $requestParams = $this->_request->getParams();

        $scopeParams = array(
            'scope' => \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            'scope_id' => null,
        );

        if(array_key_exists('store', $requestParams)){
            $scopeParams['scope'] = 'stores';
            $scopeParams['scope_id'] = $requestParams['store'];
        }

        if(array_key_exists('website', $requestParams)){
            $scopeParams['scope'] = 'websites';
            $scopeParams['scope_id'] = $requestParams['website'];
        }

        return $scopeParams;
    }

    /**
     * @return mixed
     */
    public function getScopeType()
    {
        $scopeData = $this->getScope();
        return $scopeData['scope'];
    }

    /**
     * @return mixed
     */
    public function getScopeId()
    {
        $scopeData = $this->getScope();
        return $scopeData['scope_id'];
    }
}