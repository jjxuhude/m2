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

class Condition extends Generic implements TabInterface
{

    /**
     * @var \Magento\Backend\Block\Widget\Form\Renderer\Fieldset
     */
    protected $_rendererFieldset;

    /**
     * @var \Magento\Rule\Block\Conditions
     */
    protected $_conditions;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_customerGroupCollection;

    /**
     * Condition constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Rule\Block\Conditions $conditions
     * @param \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroupCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Rule\Block\Conditions $conditions,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroupCollection,
        array $data = []
    ) {
        $this->_rendererFieldset = $rendererFieldset;
        $this->_conditions = $conditions;
        $this->_customerGroupCollection = $customerGroupCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareLayout()
    {
        \Magento\Framework\Data\Form::setFieldsetRenderer(
            $this->getLayout()->createBlock(
                'TemplateMonster\ProductLabels\Block\Adminhtml\Renderer\FieldsetAccordion',
                $this->getNameInLayout() . '_fieldset'
            )
        );
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
        //$model = $this->_coreRegistry->registry('current_promo_catalog_rule');
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
        $form->setHtmlIdPrefix('rule_');

        $renderer = $this->_rendererFieldset->setTemplate(
            'TemplateMonster_ProductLabels::promo/fieldset.phtml'
        )->setNewChildUrl(
            $this->getUrl('catalog_rule/promo_catalog/newConditionHtml/form/rule_conditions_fieldset')
        );

        $fieldset = $form->addFieldset(
            'conditions_fieldset',
            ['legend' => __('Conditions (don\'t add conditions if rule is applied to all products)')]
        )->setRenderer(
            $renderer
        );

        $fieldset->addField('conditions', 'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        )->setRule(
            $model
        )->setRenderer(
            $this->_conditions
        );

        $fieldsetDateRange = $form->addFieldset('date_range_fieldset', ['legend' => __('Date Range')]);
        $fieldsetDateRange->addField('use_date_range', 'select',
            [
                'label' => __('Use Date Range'),
                'title' => __('Use Date Range'),
                'name' => 'use_date_range',
                'required' => true,
                'values' => [
                    0 => __('No'),
                    1 => __('Yes')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange->addField('from_date', 'date',
            [
                'label' => __('From Date'),
                'title' => __('From Date'),
                'name' => 'from_date',
                'date_format' => 'yyyy-MM-dd',
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange->addField('from_time', 'text',
            [
                'name' => 'from_time',
                'label' => __('From Time'),
                'title' => __('From Time'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange->addField('to_date', 'date',
            [
                'label' => __('To Date'),
                'title' => __('To Date'),
                'name' => 'to_date',
                'date_format' => 'yyyy-MM-dd',
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange->addField('to_time', 'text',
            [
                'name' => 'to_time',
                'label' => __('To Time'),
                'title' => __('To Time'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );


        $fieldsetState = $form->addFieldset('state_fieldset', ['legend' => __('State')]);
        $fieldsetState->addField('is_new', 'select',
            [
                'label' => __('Is New'),
                'title' => __('Is New'),
                'name' => 'is_new',
                'required' => true,
                'values' => [
                    0 => __('No Use'),
                    1 => __('No'),
                    2 => __('Yes')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetState->addField('is_on_sale', 'select',
            [
                'label' => __('Is On Sale'),
                'title' => __('Is On Sale'),
                'name' => 'is_on_sale',
                'required' => true,
                'values' => [
                    0 => __('No Use'),
                    1 => __('No'),
                    2 => __('Yes')
                ],
                'disabled' => $isElementDisabled
            ]
        );


        $fieldsetStock = $form->addFieldset('stock_fieldset', ['legend' => __('Stock')]);
        $fieldsetStock->addField('stock_status', 'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'stock_status',
                'required' => true,
                'values' => [
                    0 => __('No Use'),
                    1 => __('No'),
                    2 => __('Yes')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange = $form->addFieldset('price_range_fieldset', ['legend' => __('Price Range')]);
        $fieldsetDateRange->addField('use_price_range', 'select',
            [
                'label' => __('Use Price Range'),
                'title' => __('Use Price Range'),
                'name' => 'use_price_range',
                'required' => true,
                'values' => [
                    0 => __('No'),
                    1 => __('Yes')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange->addField('by_price', 'select',
            [
                'label' => __('By Price'),
                'title' => __('By Price'),
                'name' => 'by_price',
                'required' => true,
                'values' => [
                    'price' => __('Base Price'),
                    'special_price' => __('Special Price'),
                    'final_price' => __('Final Price'),
                    'final_price_incl_tax' => __('Final Price Incl Tax'),
                    'starting_from_price' => __('Starting From Price'),
                    'starting_to_price' => __('Starting To Price'),

                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange->addField('from_price', 'text',
            [
                'name' => 'from_price',
                'label' => __('From Price'),
                'title' => __('From Price'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetDateRange->addField('to_price', 'text',
            [
                'name' => 'to_price',
                'label' => __('To Price'),
                'title' => __('To Price'),
                'required' => true,
                'disabled' => $isElementDisabled
            ]
        );


        $fieldsetCustomerGroup = $form->addFieldset('customer_group_fieldset', ['legend' => __('Customer Group')]);

        $fieldsetCustomerGroup->addField('use_customer_group', 'select',
            [
                'label' => __('Use Customer Group'),
                'title' => __('Use Customer Group'),
                'name' => 'use_customer_group',
                'required' => true,
                'values' => [
                    0 => __('No'),
                    1 => __('Yes')
                ],
                'disabled' => $isElementDisabled
            ]
        );

        $fieldsetCustomerGroup->addField('customer_group_ids', 'multiselect',
            [
                'label' => __('For Customer Group'),
                'title' => __('For Customer Group'),
                'name' => 'customer_group_ids',
                'required' => true,
                'values' => $this->_customerGroupCollection->toOptionArray(),
                'disabled' => $isElementDisabled
            ]
        );

        // define field dependencies
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Element\Dependence'
            //Date Range Start
            )->addFieldMap(
                "rule_use_date_range",
                'use_date_range'
            )->addFieldMap(
                "rule_from_date",
                'from_date'
            )->addFieldMap(
                "rule_from_time",
                'from_time'
            )->addFieldMap(
                "rule_to_date",
                'to_date'
            )->addFieldMap(
                "rule_to_time",
                'to_time'
            )
                //Date Range End

                //Price Range Start
                ->addFieldMap(
                    "rule_use_price_range",
                    'use_price_range'
                )->addFieldMap(
                    "rule_by_price",
                    'by_price'
                )->addFieldMap(
                    "rule_from_price",
                    'from_price'
                )->addFieldMap(
                    "rule_to_price",
                    'to_price'
                )
                //Price Range End

                //Customer Group Start
                ->addFieldMap(
                    "rule_use_customer_group",
                    'use_customer_group'
                )->addFieldMap(
                    "rule_customer_group_ids",
                    'customer_group_ids'
                )
                //Customer Group End

                //Date Range Start
                ->addFieldDependence(
                    'from_date',
                    'use_date_range',
                    1
                )->addFieldDependence(
                    'from_time',
                    'use_date_range',
                    1
                )->addFieldDependence(
                    'to_date',
                    'use_date_range',
                    1
                )->addFieldDependence(
                    'to_time',
                    'use_date_range',
                    1
                )
                //Date Range End

                //Price Range Start
                ->addFieldDependence(
                    'by_price',
                    'use_price_range',
                    1
                )->addFieldDependence(
                    'from_price',
                    'use_price_range',
                    1
                )->addFieldDependence(
                    'to_price',
                    'use_price_range',
                    1
                )
                //Price Range End

                //Customer Group Start
                ->addFieldDependence(
                    'customer_group_ids',
                    'use_customer_group',
                    1
                )
        //Customer Group End
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
        return __('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Conditions');
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
