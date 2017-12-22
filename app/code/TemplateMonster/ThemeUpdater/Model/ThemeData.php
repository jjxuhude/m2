<?php
namespace TemplateMonster\ThemeUpdater\Model;

use Magento\Backend\Block\Template\Context;
use Magento\Theme\Model\Theme;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Json\Decoder as JsonDecoder;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Magento\Framework\App\Request\Http as Request;

class ThemeData extends \Magento\Framework\DataObject
{
    const GENERAL_VERSION = 'themeupdater/general/version';
    const DESIGN_THEME_ID = 'design/theme/theme_id';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Theme
     */
    protected $_theme;

    /**
     * @var ComponentRegistrar
     */
    protected $_componentRegistrar;

    /**
     * @var\ Magento\Framework\Json\Decoder
     */
    protected $_jsonDecoder;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_fileDriver;

    /**
     * @var Request
     */
    protected $_request;


    public function __construct(
        Context $context,
        Theme $theme,
        ComponentRegistrar $componentRegistrar,
        JsonDecoder $jsonDecoder,
        FileDriver $fileDriver,
        Request $request,
        array $data = [])
    {
        $this->_scopeConfig = $context->getScopeConfig();
        $this->_theme = $theme;
        $this->_componentRegistrar = $componentRegistrar;
        $this->_jsonDecoder = $jsonDecoder;
        $this->_fileDriver = $fileDriver;
        $this->_request = $request;

        parent::__construct($data);
    }

    /**
     * Get theme Id from scope config
     *
     * @return mixed
     */
    public function getThemeId()
    {
        return $this->_scopeConfig->getValue(self::DESIGN_THEME_ID, $this->getScopeType(), $this->getScopeId() );
    }

    /**
     * Get theme path
     *
     * @return mixed|string
     */
    public function getAbsThemePath()
    {
        $theme = $this->_theme->load($this->getThemeId());
        $result = $this->_componentRegistrar->getPath(ComponentRegistrar::THEME, 'frontend/' . $theme->getThemePath() );
		//return 'C:\wamp\www\m2\app\design\frontend\TemplateMonster\theme059';

        return $result;
    }

    /**
     * Get json data from theme composer file
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function getComposerData()
    {
        $path       = $this->getAbsThemePath() . '/composer.json';
        $resource   = $this->_fileDriver->fileOpen($path, 'r');
        $data       = $this->_fileDriver->fileRead($resource, filesize($path));
        $result     = $this->_jsonDecoder->decode($data);

        $this->_fileDriver->fileClose($resource);

        return $result;
    }

    /**
     * Get theme version from composer data
     *
     * @return mixed
     */
    public function getThemeVersionJson()
    {
        $jsonData = $this->getComposerData();
        $version = $jsonData['version'];

        return $version;
    }

    /**
     * Get current scope(store, website) and it's id
     *
     * @return array
     */
    public function getScope()
    {
        $requestParams = $this->_request->getParams();

        $scopeParams = array(
            'scope' => 'default',
            'scope_id' => null,
        );

        if(key_exists('store', $requestParams)){
            $scopeParams['scope'] = 'stores';
            $scopeParams['scope_id'] = $requestParams['store'];
        }

        if(key_exists('website', $requestParams)){
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

    /**
     * @return mixed
     */
    public function getVersion()
    {
        $zeroVersion = '0.0.0';

        $_scopeType = $this->getScopeType() ? $this->getScopeType() : '';
        $_scopeId = $this->getScopeId() ? $this->getScopeId() : '';

        $scopeConfigValue =  $this->_scopeConfig->getValue(self::GENERAL_VERSION, $_scopeType, $_scopeId );
        $composerValue = $this->getThemeVersionJson();

        if(!isset($scopeConfigValue)){
            $scopeConfigValue = $zeroVersion;
        }
        if(!isset($composerValue)){
            $composerValue = $zeroVersion;
        }

        if(version_compare($scopeConfigValue, $composerValue) >= 0){
            $result = $scopeConfigValue;
        } else {
            $result = $composerValue;
        }

        return $result;
    }

    /**
     * Get theme name
     *
     * @return string
     */
    public function getThemeName()
    {
        return basename($this->getAbsThemePath());
    }
}