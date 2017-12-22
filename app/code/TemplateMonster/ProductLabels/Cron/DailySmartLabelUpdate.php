<?php

namespace TemplateMonster\ProductLabels\Cron;

use TemplateMonster\ProductLabels\Model\Indexer\Label\SmartLabel\SmartLabelProductProcessor;

class DailySmartLabelUpdate
{

    /**
     * @var SmartLabelProductProcessor
     */
    protected $_smartLabelProductProcessor;

    /**
     * DailySmartLabelUpdate constructor.
     * @param SmartLabelProductProcessor $smartLabelProductProcessor
     */
    public function __construct(SmartLabelProductProcessor $smartLabelProductProcessor)
    {
        $this->_smartLabelProductProcessor = $smartLabelProductProcessor;
    }

    public function execute()
    {
        $this->_smartLabelProductProcessor->markIndexerAsInvalid();
    }
}
