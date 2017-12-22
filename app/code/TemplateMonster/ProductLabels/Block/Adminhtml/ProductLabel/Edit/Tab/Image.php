<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Block\Adminhtml\ProductLabel\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Image extends Generic implements TabInterface
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry(\TemplateMonster\ProductLabels\Api\Data\ProductLabelInterface::REGISTRY_NAME);

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('TemplateMonster_ProductLabels::productlabels_save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('smart_label_');

        $fieldset = $form->addFieldset('product_image_fieldset', ['legend' => __('Product Page')]);

        $fieldset->addField(
            'product_label_status',
            'select',
            [
                'label' => __('Label Status'),
                'title' => __('Label Status'),
                'name' => 'product_label_status',
                'required' => false,
//                'after_element_html' => __('Set the type of Label. Text or Image.'),
                'values' => [
                    'disable' =>__('Disable'),
                    'enable' =>__('Enable')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_label_type',
            'select',
            [
                'label' => __('Label Type'),
                'title' => __('Label Type'),
                'name' => 'product_label_type',
                'required' => false,
//                'after_element_html' => __('Set the type of Label. Text or Image.'),
                'values' => [
                    'text' =>__('Text'),
                    'image' =>__('Image')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_image_label',
            'image',
            [
                'label' => __('Image'),
                'title' => __('Image'),
                'name' => 'product_image_label',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_image_position',
            'select',
            [
                'label' => __('Label Position'),
                'title' => __('Label Position'),
                'name' => 'product_image_position',
//                'after_element_html' => '<span>'. __('Set the Label position.').'</span>',
                'required' => false,
                'values' => [
                    'right_top' =>__('Right/Top'),
                    'left_top' =>__('Left/Top'),
                    'center_top' =>__('Center/Top'),
                    'right_middle' =>__('Right/Middle'),
                    'left_middle' =>__('Left/Middle'),
                    'center_middle' =>__('Center/Middle'),
                    'right_bottom' =>__('Right/Bottom'),
                    'left_bottom' =>__('Left/Bottom'),
                    'center_bottom' =>__('Center/Bottom'),
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_image_container',
            'text',
            [
                'name' => 'product_image_container',
                'label' => __('Label css class'),
                'title' => __('Label css class'),
//                'after_element_html' => __('Set css class for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_image_width',
            'text',
            [
                'name' => 'product_image_width',
                'label' => __('Label width'),
                'title' => __('Label width'),
//                'after_element_html' => __('Set Label width in pixels.'),
                'class' => 'validate-css-length',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_image_height',
            'text',
            [
                'name' => 'product_image_height',
                'label' => __('Label height'),
                'title' => __('Label height'),
//                'after_element_html' => __('Set Label height in pixels.'),
                'class' => 'validate-css-length',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_image_css',
            'textarea',
            [
                'name' => 'product_image_css',
                'label' => __('Custom CSS'),
                'title' => __('Custom CSS'),
//                'after_element_html' => __('Custom CSS for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_background',
            'image',
            [
                'label' => __('Background image'),
                'title' => __('Background image'),
                'name' => 'product_text_background',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_comment',
            'text',
            [
                'name' => 'product_text_comment',
                'label' => __('Text'),
                'title' => __('Text'),
//                'after_element_html' => __('Text for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_label_position',
            'select',
            [
                'label' => __('Label Position'),
                'title' => __('Label Position'),
                'name' => 'product_text_label_position',
//                'after_element_html' => __('Set the Label position.'),
                'required' => false,
                'values' => [
                    'right_top' =>__('Right/Top'),
                    'left_top' =>__('Left/Top'),
                    'center_top' =>__('Center/Top'),
                    'right_middle' =>__('Right/Middle'),
                    'left_middle' =>__('Left/Middle'),
                    'center_middle' =>__('Center/Middle'),
                    'right_bottom' =>__('Right/Bottom'),
                    'left_bottom' =>__('Left/Bottom'),
                    'center_bottom' =>__('Center/Bottom'),
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_fontsize',
            'text',
            [
                'name' => 'product_text_fontsize',
                'label' => __('Fonts size'),
                'title' => __('Fonts size'),
//                'after_element_html' => __('Set font size for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_fontcolor',
            'text',
            [
                'name' => 'product_text_fontcolor',
                'label' => __('Font color'),
                'title' => __('Font color'),
                'class' => 'field-color-picker',
//                'after_element_html' => __('Set color for Label.'),
                'required' => false,
                'disabled' => true
            ]
        );

        $fieldset->addField(
            'product_text_position',
            'select',
            [
                'label' => __('Text position'),
                'title' => __('Text position'),
                'name' => 'product_text_position',
//                'after_element_html' => __('Set the text position into Label.'),
                'required' => false,
                'values' => [
                    'right' =>__('Right'),
                    'left' =>__('Left'),
                    'center' =>__('Center'),

                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_container',
            'text',
            [
                'name' => 'product_text_container',
                'label' => __('Label css class'),
                'title' => __('Label css class'),
//                'after_element_html' => __('Set css class for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_width',
            'text',
            [
                'name' => 'product_text_width',
                'label' => __('Label width'),
                'title' => __('Label width'),
//                'after_element_html' => __('Set Label width in pixels.'),
                'required' => false,
                'class' => 'validate-css-length',
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_height',
            'text',
            [
                'name' => 'product_text_height',
                'label' => __('Label height'),
                'title' => __('Label height'),
//                'after_element_html' => __('Set Label height in pixels.'),
                'required' => false,
                'class' => 'validate-css-length',
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'product_text_css',
            'textarea',
            [
                'name' => 'product_text_css',
                'label' => __('Custom CSS'),
                'title' => __('Custom CSS'),
//                'after_element_html' => __('Custom CSS for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory = $form->addFieldset('category_image_fieldset', ['legend' => __('Category Page')]);

        $fieldsetCategory->addField(
            'category_label_status',
            'select',
            [
                'label' => __('Label Status'),
                'title' => __('Label Status'),
                'name' => 'category_label_status',
                'required' => false,
//                'after_element_html' => __('Set the type of Label. Text or Image.'),
                'values' => [
                    'disable' =>__('Disable'),
                    'enable' =>__('Enable')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_label_type',
            'select',
            [
                'label' => __('Label Type'),
                'title' => __('Label Type'),
                'name' => 'category_label_type',
//                'after_element_html' => __('Set the type of Label. Text or Image.'),
                'required' => false,
                'values' => [
                    'text' =>__('Text'),
                    'image' =>__('Image')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_image_label',
            'image',
            [
                'label' => __('Image'),
                'title' => __('Image'),
                'name' => 'category_image_label',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_image_position',
            'select',
            [
                'label' => __('Label Position'),
                'title' => __('Label Position'),
                'name' => 'category_image_position',
                'required' => false,
                'values' => [
                    'right_top' =>__('Right/Top'),
                    'left_top' =>__('Left/Top'),
                    'center_top' =>__('Center/Top'),
                    'right_middle' =>__('Right/Middle'),
                    'left_middle' =>__('Left/Middle'),
                    'center_middle' =>__('Center/Middle'),
                    'right_bottom' =>__('Right/Bottom'),
                    'left_bottom' =>__('Left/Bottom'),
                    'center_bottom' =>__('Center/Bottom'),
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_image_container',
            'text',
            [
                'name' => 'category_image_container',
                'label' => __('Label css class'),
                'title' => __('Label css class'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_image_width',
            'text',
            [
                'name' => 'category_image_width',
                'label' => __('Label width'),
                'title' => __('Label width'),
                'class' => 'validate-css-length',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_image_height',
            'text',
            [
                'name' => 'category_image_height',
                'label' => __('Label height'),
                'title' => __('Label height'),
                'class' => 'validate-css-length',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_image_css',
            'textarea',
            [
                'name' => 'category_image_css',
                'label' => __('Custom CSS'),
                'title' => __('Custom CSS'),
//                'after_element_html' => __('Custom CSS for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_background',
            'image',
            [
                'label' => __('Background image'),
                'title' => __('Background image'),
                'name' => 'category_text_background',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_comment',
            'text',
            [
                'name' => 'category_text_comment',
                'label' => __('Text'),
                'title' => __('Text'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_label_position',
            'select',
            [
                'label' => __('Label Position'),
                'title' => __('Label Position'),
                'name' => 'category_text_label_position',
                'required' => false,
                'values' => [
                    'right_top' =>__('Right/Top'),
                    'left_top' =>__('Left/Top'),
                    'center_top' =>__('Center/Top'),
                    'right_middle' =>__('Right/Middle'),
                    'left_middle' =>__('Left/Middle'),
                    'center_middle' =>__('Center/Middle'),
                    'right_bottom' =>__('Right/Bottom'),
                    'left_bottom' =>__('Left/Bottom'),
                    'center_bottom' =>__('Center/Bottom'),
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_fontsize',
            'text',
            [
                'name' => 'category_text_fontsize',
                'label' => __('Fonts size'),
                'title' => __('Fonts size'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_fontcolor',
            'text',
            [
                'name' => 'category_text_fontcolor',
                'label' => __('Font color'),
                'title' => __('Font color'),
                'class' => 'field-color-picker',
                'required' => false,
                'disabled' => true
            ]
        );

        $fieldsetCategory->addField(
            'category_text_position',
            'select',
            [
                'label' => __('Text position'),
                'title' => __('Text position'),
                'name' => 'category_text_position',
                'required' => false,
                'values' => [
                    'right' =>__('Right'),
                    'left' =>__('Left'),
                    'center' =>__('Center'),

                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_container',
            'text',
            [
                'name' => 'category_text_container',
                'label' => __('Label css class'),
                'title' => __('Label css class'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_width',
            'text',
            [
                'name' => 'category_text_width',
                'label' => __('Label width'),
                'title' => __('Label width'),
                'class' => 'validate-css-length',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_height',
            'text',
            [
                'name' => 'category_text_height',
                'label' => __('Label height'),
                'title' => __('Label height'),
                'class' => 'validate-css-length',
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCategory->addField(
            'category_text_css',
            'textarea',
            [
                'name' => 'category_text_css',
                'label' => __('Custom CSS'),
                'title' => __('Custom CSS'),
//                'after_element_html' => __('Custom CSS for Label.'),
                'required' => false,
                'disabled' => $isElementDisabled
            ]
        );
        
        // define field dependencies
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Element\Dependence'
            )->addFieldMap(
                "smart_label_product_label_type",
                'product_label_type'
            )
            ->addFieldMap(
                "smart_label_product_image_label",
                'product_image_label'
            )
            ->addFieldMap(
                "smart_label_product_image_position",
                'product_image_position'
            )
            ->addFieldMap(
                "smart_label_product_image_container",
                'product_image_container'
            )
            ->addFieldMap(
                "smart_label_product_image_width",
                'product_image_width'
            )
            ->addFieldMap(
                "smart_label_product_image_height",
                'product_image_height'
            )
            ->addFieldMap(
                "smart_label_product_image_css",
                'product_image_css'
            )
            ->addFieldMap(
                "smart_label_product_text_background",
                'product_text_background'
            )
            ->addFieldMap(
                "smart_label_product_text_comment",
                'product_text_comment'
            )
            ->addFieldMap(
                "smart_label_product_text_label_position",
                'product_text_label_position'
            )
            ->addFieldMap(
                "smart_label_product_text_fontsize",
                'product_text_fontsize'
            )
            ->addFieldMap(
                "smart_label_product_text_fontcolor",
                'product_text_fontcolor'
            )
            ->addFieldMap(
                "smart_label_product_text_position",
                'product_text_position'
            )
            ->addFieldMap(
                "smart_label_product_text_container",
                'product_text_container'
            )
            ->addFieldMap(
                "smart_label_product_text_width",
                'product_text_width'
            )
            ->addFieldMap(
                "smart_label_product_text_height",
                'product_text_height'
            )
            ->addFieldMap(
                "smart_label_product_text_css",
                'product_text_css'
            )
            ->addFieldDependence(
                'product_image_label',
                'product_label_type',
                'image'
            )
            ->addFieldDependence(
                'product_image_position',
                'product_label_type',
                'image'
            )
            ->addFieldDependence(
                'product_image_container',
                'product_label_type',
                'image'
            )
            ->addFieldDependence(
                'product_image_width',
                'product_label_type',
                'image'
            )
            ->addFieldDependence(
                'product_image_height',
                'product_label_type',
                'image'
            )
            ->addFieldDependence(
                'product_image_css',
                'product_label_type',
                'image'
            )
            ->addFieldDependence(
                'product_text_background',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_comment',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_label_position',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_fontsize',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_fontcolor',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_position',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_container',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_width',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_height',
                'product_label_type',
                'text'
            )
            ->addFieldDependence(
                'product_text_css',
                'product_label_type',
                'text'
            )
                ->addFieldMap(
                    "smart_label_category_label_type",
                    'category_label_type'
                )
                ->addFieldMap(
                    "smart_label_category_image_label",
                    'category_image_label'
                )
                ->addFieldMap(
                    "smart_label_category_image_position",
                    'category_image_position'
                )
                ->addFieldMap(
                    "smart_label_category_image_container",
                    'category_image_container'
                )
                ->addFieldMap(
                    "smart_label_category_image_width",
                    'category_image_width'
                )
                ->addFieldMap(
                    "smart_label_category_image_height",
                    'category_image_height'
                )
                ->addFieldMap(
                    "smart_label_category_image_css",
                    'category_image_css'
                )

                ->addFieldMap(
                    "smart_label_category_text_background",
                    'category_text_background'
                )
                ->addFieldMap(
                    "smart_label_category_text_comment",
                    'category_text_comment'
                )
                ->addFieldMap(
                    "smart_label_category_text_label_position",
                    'category_text_label_position'
                )
                ->addFieldMap(
                    "smart_label_category_text_fontsize",
                    'category_text_fontsize'
                )
                ->addFieldMap(
                    "smart_label_category_text_fontcolor",
                    'category_text_fontcolor'
                )
                ->addFieldMap(
                    "smart_label_category_text_position",
                    'category_text_position'
                )
                ->addFieldMap(
                    "smart_label_category_text_container",
                    'category_text_container'
                )
                ->addFieldMap(
                    "smart_label_category_text_width",
                    'category_text_width'
                )
                ->addFieldMap(
                    "smart_label_category_text_height",
                    'category_text_height'
                )
                ->addFieldMap(
                    "smart_label_category_text_css",
                    'category_text_css'
                )
                ->addFieldDependence(
                    'category_image_label',
                    'category_label_type',
                    'image'
                )
                ->addFieldDependence(
                    'category_image_position',
                    'category_label_type',
                    'image'
                )
                ->addFieldDependence(
                    'category_image_container',
                    'category_label_type',
                    'image'
                )
                ->addFieldDependence(
                    'category_image_width',
                    'category_label_type',
                    'image'
                )
                ->addFieldDependence(
                    'category_image_height',
                    'category_label_type',
                    'image'
                )
                ->addFieldDependence(
                    'category_image_css',
                    'category_label_type',
                    'image'
                )
                ->addFieldDependence(
                    'category_text_background',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_comment',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_label_position',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_fontsize',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_fontcolor',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_position',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_container',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_width',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_height',
                    'category_label_type',
                    'text'
                )
                ->addFieldDependence(
                    'category_text_css',
                    'category_label_type',
                    'text'
                )
        );


        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Images');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Images');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
