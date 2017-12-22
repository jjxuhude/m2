<?php

namespace Jjxuhuade\Smtp\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements  UpgradeSchemaInterface {
  /**
   * @var \Psr\Log\LoggerInterface
   */
  protected $_logger;

  /**
   * @param   LoggerInterface   $loggerInterface
   */
  public function __construct( \Psr\Log\LoggerInterface $loggerInterface ) {
    $this->_logger = $loggerInterface;
  }

  /**
   * {@inheritdoc}
   * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
   */
  public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
    $this->_logger->debug('[smtp] Upgrade script is running.');

    $setup->startSetup();

    $setup->endSetup();

    $this->_logger->debug('[smtp] Upgrade script is done.');
  }
}
