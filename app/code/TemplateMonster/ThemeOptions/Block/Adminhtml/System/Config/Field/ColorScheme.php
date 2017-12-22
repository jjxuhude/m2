<?php

namespace TemplateMonster\ThemeOptions\Block\Adminhtml\System\Config\Field;

use TemplateMonster\ThemeOptions\Helper\ColorScheme as ColorSchemeHelper;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\View\Helper\Js as JsHelper;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Color scheme frontend model.
 *
 * @package TemplateMonster\ThemeOptions\Block\Adminhtml\System\Config\Field
 */
class ColorScheme extends Field
{
    /**
     * @var ColorSchemeHelper
     */
    protected $_colorSchemeHelper;

    /**
     * @var JsHelper
     */
    protected $_jsHelper;

    /**
     * ColorScheme constructor.
     *
     * @param ColorSchemeHelper $colorSchemeHelper
     * @param JsHelper          $jsHelper
     * @param Context           $context
     * @param array             $data
     */
    public function __construct(
        ColorSchemeHelper $colorSchemeHelper,
        JsHelper $jsHelper,
        Context $context,
        array $data = []
    )
    {
        $this->_colorSchemeHelper = $colorSchemeHelper;
        $this->_jsHelper = $jsHelper;
        parent::__construct($context, $data);
    }

    /**
     * @inheritdoc
     */
    public function render(AbstractElement $element)
    {
        $html = parent::render($element);

        return $this->_getExtraCss() . $html . $this->_getExtraJs($element);
    }

    /**
     * @inheritdoc
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $name = $this->_getWebsiteCode();
        $oldValues = $element->getValues();
        $newValues = isset($oldValues[$name]) ? $oldValues[$name] : [];
        $element->setValues($newValues);

        $html = parent::_getElementHtml($element);
        $html .= '<div class="preview">';
        foreach ($element->getValues() as $value) {
            if($value['value']){
                $html .= sprintf(
                    '<img id="%s" src="%s" alt="" style="display:none;" />',
                    $value['value'],
                    $this->_getPreviewImage($value['value'])
                );
            }
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * Get extra CSS.
     *
     * @return string
     */
    protected function _getExtraCss()
    {
        return <<<EOL
            <style>
                .color-scheme {
                    /*width: 30px;*/
                    padding: 1px 2px;
                    min-width: 30px;
                    max-width: 90px;
                    height: 20px;
                    float: left;
                    margin: 0 10px 5px 0;
                    text-align: center;
                    cursor: pointer;
                    position: relative;
                    border: 1px solid rgb(218, 218, 218);
                    overflow: hidden;
                    text-overflow: ellipsis;
                    clear: right;
                }

                .color-scheme.selected {
                    outline: 2px solid #FF5100;
                    border: 1px solid #fff;
                    color: #333;
                }

                .color-scheme:not(.selected):hover {
                    outline: 1px solid #999;
                    border: 1px solid #fff;
                    color: #333;
                }
                
                .preview {
                    margin-top: 5px;
                }
            </style>
EOL;
    }

    /**
     * Get extra javascript.
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getExtraJs(AbstractElement $element)
    {
        list ($scopeId, $scope) = $this->_getScope();

        return <<<EOL
            <script type="text/x-magento-init">
                {
                    "#{$element->getHtmlId()}": {
                        "colorScheme": {
                            "defaultValues": {$this->_colorSchemeHelper->getJsonDefaultValues($scopeId)},
                            "userValues": {$this->_colorSchemeHelper->getJsonUserValues($scopeId, $scope)}
                        }
                    }
                }
            </script>
EOL;
    }

    /**
     * Get preview image url.
     *
     * @param string $value
     *
     * @return string
     */
    protected function _getPreviewImage($value)
    {
        return $this->getViewFileUrl(sprintf(
            'TemplateMonster_ThemeOptions::images/previews/%s/%s.png',
            $this->_getWebsiteCode(),
            $value
        ));
    }

    /**
     * Get form block.
     *
     * @return bool|\Magento\Config\Block\System\Config\Form
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getFormBlock()
    {
        /** @var \Magento\Framework\View\Element\AbstractBlock $edit */
        $edit = $this->getLayout()->getBlock('system.config.edit');
        $form = $edit->getChildBlock('form');

        return $form;
    }

    /**
     * Get scope.
     *
     * @return string
     */
    protected function _getScope()
    {
        return [
            $this->_getFormBlock()->getScopeId(),
            $this->_getFormBlock()->getScope()
        ];
    }

    /**
     * Get website code.
     *
     * @return string|null
     */
    protected function _getWebsiteCode()
    {
        if (!$this->hasData('website_code')) {
            if ($id = $this->_getFormBlock()->getStoreCode()) {
                /** @var \Magento\Store\Model\Store $store */
                $store = $this->_storeManager->getStore($id);
                $code = $store->getWebsite()->getCode();
            }
            elseif ($id = $this->_getFormBlock()->getWebsiteCode()) {
                $code = $this->_storeManager->getWebsite($id)->getCode();
            }
            else {
                $code = null;
            }
            $this->setData('website_code', $code);
        }

        return $this->getData('website_code');
    }
}