<?php
/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Model\Filter;

class UseDateRange
{

    /**
     * @var
     */
    protected $_localeDate;

    /**
     * UseDateRange constructor.
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     */
    public function __construct(\Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate)
    {
        $this->_localeDate = $localeDate;
    }

    /**
     * @param \TemplateMonster\ProductLabels\Model\ProductLabel $rule
     * @return array
     */
    public function getDateRangeCondition(\TemplateMonster\ProductLabels\Model\ProductLabel $rule)
    {
        $dateCondition = [];

        if ($rule->getUseDateRange()) {
            $fromDate = $rule->getFromDate();
            $fromTime = $rule->getFromTime();
            $toDate = $rule->getToDate();
            $toTime = $rule->getToTime();

            $fromTimeArr = explode(':', $fromTime);
            $toTimeArr = explode(':', $toTime);

            $fromDateFormat =
                $this->_localeDate->date($fromDate)->setTime($fromTimeArr[0], $fromTimeArr[1], 0)->format('Y-m-d H:i:s');
            $toDateFormat =
                $this->_localeDate->date($toDate)->setTime($toTimeArr[0], $toTimeArr[1], 0)->format('Y-m-d H:i:s');

            if ($fromDateFormat) {
                $dateCondition['from'] = $fromDateFormat;
            }

            if ($toDateFormat) {
                $dateCondition['to'] = $toDateFormat;
            }
        }
        return $dateCondition;
    }
}
