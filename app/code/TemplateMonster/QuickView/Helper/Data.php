<?php

namespace TemplateMonster\QuickView\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Data helper.
 */
class Data extends AbstractHelper
{
    /**
     * Enabled config option.
     */
    const XML_PATH_ENABLED = 'quick_view/general/enabled';

    /**
     * Button text config option.
     */
    const XML_PATH_BUTTON_TEXT = 'quick_view/general/button_text';

    /**
     * Button CSS-class config option.
     */
    const XML_PATH_BUTTON_CSS_CLASS = 'quick_view/general/button_css_class';

    /**
     * Window width config option.
     */
    const XML_PATH_WINDOW_WIDTH = 'quick_view/popup_settings/window_width';

    /**
     * Window Height config option.
     */
    const XML_PATH_WINDOW_HEIGHT = 'quick_view/popup_settings/window_height';

    /**
     * Show tabs config option.
     */
    const XML_PATH_SHOW_TABS = 'quick_view/popup_settings/show_tabs';

    /**
     * Show wishlist config option.
     */
    const XML_PATH_SHOW_ADDTO_BUTTONS = 'quick_view/popup_settings/show_addto_buttons';

    /**
     * Show review config option.
     */
    const XML_PATH_SHOW_REVIEWS = 'quick_view/popup_settings/show_reviews';

    /**
     * Show stock config option.
     */
    const XML_PATH_SHOW_STOCK = 'quick_view/popup_settings/show_stock';

    /**
     * Show sku config option.
     */
    const XML_PATH_SHOW_SKU = 'quick_view/popup_settings/show_sku';

    /**
     * Show social buttons config option.
     */
    const XML_PATH_SHOW_SOCIAL_BUTTONS = 'quick_view/popup_settings/show_social_buttons';

    /**
     * Check is module enabled.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get button text.
     *
     * @param null|string $store
     *
     * @return mixed
     */
    public function getButtonText($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_BUTTON_TEXT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Get button CSS-class.
     *
     * @param null|string $store
     *
     * @return mixed
     */
    public function getButtonCssClass($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_BUTTON_CSS_CLASS,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return mixed
     */
    public function getWindowWidth($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_WINDOW_WIDTH,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return mixed
     */
    public function getWindowHeight($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_WINDOW_HEIGHT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return bool
     */
    public function isShowTabs($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_TABS,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return bool
     */
    public function isShowAddToButtons($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_ADDTO_BUTTONS,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return bool
     */
    public function isShowReviews($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_REVIEWS,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return bool
     */
    public function isShowStock($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_STOCK,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return bool
     */
    public function isShowSku($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_SKU,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string $store
     *
     * @return mixed
     */
    public function isShowSocialButtons($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_SOCIAL_BUTTONS,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
