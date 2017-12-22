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

class Main extends Generic implements TabInterface
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Label Information')]);

        if ($model->getId()) {
            $fieldset->addField('smart_label_id', 'hidden', ['name' => 'smart_label_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'priority',
            'text',
            [
                'name' => 'priority',
                'label' => __('Priority'),
                'class' => 'validate-zero-or-greater',
                'title' => __('Priority'),
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'higher_priority',
            'select',
            [
                'label' => __('Hide if label with higher priority is already applied'),
                'title' => __('Hide if label with higher priority is already applied'),
                'name' => 'higher_priority',
                'required' => true,
                'values' => [
                    true =>__('Yes'),
                    false =>__('No')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldset->addField(
            'use_for_parent',
            'select',
            [
                'label' => __('Use for Parent'),
                'title' => __('Use for Parent'),
                'name' => 'use_for_parent',
                'required' => true,
                'values' => [
                    true =>__('Yes'),
                    false =>__('No')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'website_ids',
                'multiselect',
                [
                    'label' => __('Store'),
                    'title' => __('Store'),
                    'name' => 'website_ids[]',
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true),
                    'disabled' => $isElementDisabled
                ]
            );
            /** @var \Magento\Framework\Data\Form\Element\Renderer\RendererInterface $renderer */
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        }
        else {
            $storeId = $this->_storeManager->getStore(true)->getId();
            $fieldset->addField(
                'website_ids',
                'hidden',
                ['name' => 'website_ids[]', 'value' => $storeId]
            );
            $model->setStoreId($storeId);
        }

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
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General');
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
