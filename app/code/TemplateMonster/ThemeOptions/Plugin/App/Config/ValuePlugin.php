<?php

namespace TemplateMonster\ThemeOptions\Plugin\App\Config;

use TemplateMonster\ThemeOptions\Helper\Data as ThemeOptionsHelper;
use Magento\Framework\App\Config\Value;

/**
 * Class ValuePlugin
 *
 * @package TemplateMonster\ThemeOptions\Plugin\App\Config
 */
class ValuePlugin
{
    /**
     * @inheritdoc
     */
    public function beforeSave(Value $subject)
    {
        if ($this->_isColorSettingsValue($subject)) {
            $path = $this->_getNewPath($subject);
            $subject->setPath($path);
        }
    }

    /**
     * Check is color settings path.
     *
     * @param Value $value
     *
     * @return bool
     */
    protected function _isColorSettingsValue(Value $value)
    {
        $exceptions = [
            ThemeOptionsHelper::XML_PATH_COLOR_SCHEME_STATUS,
            ThemeOptionsHelper::XML_PATH_COLOR_SCHEME
        ];
        if (in_array($value->getPath(), $exceptions, true)) {
            return false;
        }

        return strpos($value->getPath(), ThemeOptionsHelper::XML_PATH_COLOR_SETTING_GROUP) === 0;
    }

    /**
     * Get new path.
     *
     * @param Value $value
     *
     * @return string
     */
    protected function _getNewPath(Value $value)
    {
        $path = explode('/', $value->getPath());
        $i = count($path) - 1;
        array_splice($path, $i, 0, [$this->_getColorScheme($value)]);

        return implode('/', $path);
    }

    /**
     * Get color scheme.
     *
     * @param Value $value
     *
     * @return mixed
     */
    protected function _getColorScheme(Value $value)
    {
        return $value->getDataByPath('groups/color_settings/fields/color_scheme/value');
//        return $value->getDataByPath('fieldset_data/color_scheme');
    }
}