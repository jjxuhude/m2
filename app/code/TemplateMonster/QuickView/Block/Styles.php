<?php

namespace TemplateMonster\QuickView\Block;

/**
 * Class Styles.
 */
class Styles extends AbstractBlock
{
    /**
     * @var string
     */
    protected $_template = 'styles.phtml';

    /**
     * Get window width.
     *
     * @return mixed
     */
    public function getWindowWidth()
    {
        return $this->_quickViewHelper->getWindowWidth();
    }

    /**
     * Get window height.
     *
     * @return mixed
     */
    public function getWindowHeight()
    {
        return $this->_quickViewHelper->getWindowHeight();
    }
}
