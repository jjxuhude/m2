<?php
/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Plugin\Indexer;

use TemplateMonster\ProductLabels\Model\Indexer\Label\SmartLabel\SmartLabelProductProcessor;

class Website
{

    /**
     * @var
     */
    protected $_smartLabelProductProcessor;

    /**
     * Website constructor.
     * @param SmartLabelProductProcessor $smartLabelProductProcessor
     */
    public function __construct(SmartLabelProductProcessor $smartLabelProductProcessor)
    {
        $this->_smartLabelProductProcessor = $smartLabelProductProcessor;
    }

    /**
     * Invalidate catalog price rule indexer
     *
     * @param \Magento\Store\Model\Website $subject
     * @param \Magento\Store\Model\Website $result
     * @return \Magento\Store\Model\Website
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        \Magento\Store\Model\Website $subject,
        \Magento\Store\Model\Website $result
    ) {
        $this->_smartLabelProductProcessor->markIndexerAsInvalid();
        return $result;
    }
}
