<?php

namespace Jjxuhuade\Smtp\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * @codeCoverageIgnore
 */
class Recurring implements InstallSchemaInterface {
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
  public function install( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
    $this->_logger->debug('[smtp] Recurring script is running.');

    $setup->startSetup();
    
    $sql1='DROP TABLE IF EXISTS `smtppro_email_log`';
    $sql2=<<<SQL2
CREATE TABLE `smtppro_email_log` (
  `email_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email_to` varchar(255) NOT NULL DEFAULT '',
  `template` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `email_body` text,
  `created` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`email_id`),
  KEY `log_at` (`log_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL2;

    $setup->run($sql1);
    $setup->run($sql2);

    $setup->endSetup();

    $this->_logger->debug('[smtp] Recurring script is done.');
  }
}