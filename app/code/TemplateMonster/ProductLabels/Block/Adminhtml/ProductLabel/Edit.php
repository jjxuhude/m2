<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */
namespace TemplateMonster\ProductLabels\Block\Adminhtml\ProductLabel;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{

    public function _construct()
    {
        $this->_objectId = 'smart_label_id';
        $this->_blockGroup = 'TemplateMonster_ProductLabels';
        $this->_controller = 'adminhtml_productLabel';
        parent::_construct();

        if ($this->_isAllowedAction('TemplateMonster_ProductLabels::productlabels_save')) {
            $this->buttonList->update('save', 'label', __('Save '));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
            $this->buttonList->remove('saveandcontinue');
        }
    }

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
