<?php

namespace Jjxuhuade\Smtp\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface {
  /**
   * @var Logger
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
  public function install( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
    $this->_logger->debug('[smtp] Install script is running.');
    $setup->startSetup();

   
    $setup->endSetup();

    $this->_logger->debug('[smtp] Install script is done.');
  }
}