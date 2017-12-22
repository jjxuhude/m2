<?php

namespace TemplateMonster\QuickView\Plugin\Controller\Product;

use Magento\Framework\View\LayoutInterface;
use Magento\Catalog\Controller\Product\View as ProductView;
use Magento\Framework\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use TemplateMonster\QuickView\Helper\Data as QuickViewHelper;

/**
 * Class View.
 */
class View
{
    protected $_quickViewHelper;

    /**
     * @var ResultFactory
     */
    protected $_resultFactory;

    /**
     * Quick view activation param name.
     */
    const ACTIVATION_PARAM = 'quickView';

    public function __construct(
        QuickViewHelper $quickViewHelper,
        ResultFactory $resultFactory
    ) {
        $this->_quickViewHelper = $quickViewHelper;
        $this->_resultFactory = $resultFactory;
    }

    /**
     * After execute method plugin.
     *
     * @param ProductView $subject
     * @param mixed       $page
     *
     * @return $this
     */
    public function afterExecute(ProductView $subject, $page)
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $subject->getRequest();

        if (!$request->isXmlHttpRequest()) {
            return $page;
        }

        if (!$request->getParam(self::ACTIVATION_PARAM)) {
            return $page;
        }

        /** @var \Magento\Framework\Controller\Result\Json  $result */
        $result = $this->_resultFactory->create(ResultFactory::TYPE_JSON);
        if ($page instanceof Page) {
            $layout = $page->getLayout();

            return $result->setData([
                'success' => true,
                'content' => $this->_customizeLayout($layout)->renderElement('content'),
            ]);
        }

        return $result->setData([
            'error' => true,
            'message' => __('There is an error occurred while performing the request.'),
        ]);
    }

    /**
     * Customize layout.
     *
     * @param LayoutInterface $layout
     *
     * @return mixed
     */
    protected function _customizeLayout(LayoutInterface $layout)
    {
        foreach ($this->_getEnabledBlocks() as $name => $enabled) {
            if (!$enabled) {
                $layout->unsetElement($name);
            }
        }

        return $layout;
    }

    /**
     * Get enabled blocks.
     *
     * @return array
     */
    protected function _getEnabledBlocks()
    {
        return [
            'product.info.details' => $this->_quickViewHelper->isShowTabs(),
            'product.info.sku' => $this->_quickViewHelper->isShowSku(),
            'product.info.review' => $this->_quickViewHelper->isShowReviews(),
            'product.info.type' => $this->_quickViewHelper->isShowStock(),
            'product.info.addto' => $this->_quickViewHelper->isShowAddToButtons(),
            'social.sharing' => $this->_quickViewHelper->isShowSocialButtons(),
        ];
    }
}
