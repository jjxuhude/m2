<?php

namespace TemplateMonster\ProductLabels\Plugin\Indexer\Product;

use TemplateMonster\ProductLabels\Model\Indexer\Label\SmartLabel\SmartLabelProductProcessor;
use TemplateMonster\ProductLabels\Model\ResourceModel\ProductLabel\CollectionFactory as SmartLabelCollectionFactory;
use Magento\CatalogRule\Model\Rule;
use Magento\CatalogRule\Model\Rule\Condition\Combine;
use Magento\Framework\Message\ManagerInterface;
use Magento\Rule\Model\Condition\Product\AbstractProduct;

class Attribute
{
    /**
     * @var SmartLabelCollectionFactory
     */
    protected $_smartLabelCollectionFactory;

    /**
     * @var SmartLabelProductProcessor
     */
    protected $_smartLabelProductProcessor;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * Attribute constructor.
     * @param SmartLabelCollectionFactory $smartLabelCollectionFactory
     * @param SmartLabelProductProcessor $smartLabelProductProcessor
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        SmartLabelCollectionFactory $smartLabelCollectionFactory,
        SmartLabelProductProcessor $smartLabelProductProcessor,
        ManagerInterface $messageManager
    ) {
        $this->_smartLabelCollectionFactory = $smartLabelCollectionFactory;
        $this->_smartLabelProductProcessor = $smartLabelProductProcessor;
        $this->messageManager = $messageManager;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $subject
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @return \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $subject,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
    ) {
        if ($attribute->dataHasChangedFor('is_used_for_promo_rules') && !$attribute->getIsUsedForPromoRules()) {
            $this->checkCatalogRulesAvailability($attribute->getAttributeCode());
        }
        return $attribute;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $subject
     * @param \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
     * @return \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $subject,
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute
    ) {
        if ($attribute->getIsUsedForPromoRules()) {
            $this->checkCatalogRulesAvailability($attribute->getAttributeCode());
        }
        return $attribute;
    }

    /**
     * Check rules that contains affected attribute
     * If rules were found they will be set to inactive and notice will be add to admin session
     *
     * @param string $attributeCode
     * @return $this
     */
    protected function checkCatalogRulesAvailability($attributeCode)
    {
        $collection = $this->_smartLabelCollectionFactory->create()->addAttributeInConditionFilter($attributeCode);

        $disabledRulesCount = 0;
        foreach ($collection as $rule) {
            /* @var $rule Rule */
            $rule->setIsActive(0);
            /* @var $rule->getConditions() Combine */
            $this->removeAttributeFromConditions($rule->getConditions(), $attributeCode);
            $rule->save();

            $disabledRulesCount++;
        }

        if ($disabledRulesCount) {
            $this->_smartLabelProductProcessor->markIndexerAsInvalid();
            $this->messageManager->addWarning(
                __(
                    'You disabled %1 Smart Label based on "%2" attribute.',
                    $disabledRulesCount,
                    $attributeCode
                )
            );
        }

        return $this;
    }

    /**
     * Remove catalog attribute condition by attribute code from rule conditions
     *
     * @param Combine $combine
     * @param string $attributeCode
     * @return void
     */
    protected function removeAttributeFromConditions(Combine $combine, $attributeCode)
    {
        $conditions = $combine->getConditions();
        foreach ($conditions as $conditionId => $condition) {
            if ($condition instanceof Combine) {
                $this->removeAttributeFromConditions($condition, $attributeCode);
            }
            if ($condition instanceof AbstractProduct) {
                if ($condition->getAttribute() == $attributeCode) {
                    unset($conditions[$conditionId]);
                }
            }
        }
        $combine->setConditions($conditions);
    }
}
