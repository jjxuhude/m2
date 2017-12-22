<?php

namespace TemplateMonster\ThemeUpdater\Model\Config\Source;

use  TemplateMonster\ThemeUpdater\Helper\Data as Helper;

class Themes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var Helper
     */
    protected $_helper;

    public function __construct(
        Helper $helper
    )
    {
        $this->_helper = $helper;
    }
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        foreach($this->_helper->getTmThemes() as $theme){
            $options[] = array(
                'value' => $theme['theme_id'],
                'label' => $theme['theme_title']
            );
        }

        return $options;
    }
}
