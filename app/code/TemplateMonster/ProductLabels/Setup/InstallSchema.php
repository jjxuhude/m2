<?php

/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */

namespace TemplateMonster\ProductLabels\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'Smart Label Product'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('smart_label_product'))
            //GENERAL SETTINGS START
            ->addColumn(
                'smart_label_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Smart Label Rule'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Name'
            )
            ->addColumn(
                'priority',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                2,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Priority'
            )
            ->addColumn(
                'higher_priority',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Higher Priority'
            )
            ->addColumn(
                'use_for_parent',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Use For Parent'
            )
            ->addColumn(
                'website_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Store'
            )
            //GENERAL SETTINGS END

            //IMAGES PRODUCT SETTINGS START
            ->addColumn(
                'product_label_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Product Label Status'
            )
            ->addColumn(
                'product_label_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Product Label Type'
            )
            ->addColumn(
                'product_image_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Image Label'
            )
            ->addColumn(
                'product_image_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Image Position'
            )
            ->addColumn(
                'product_image_container',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Image Container'
            )
            ->addColumn(
                'product_image_width',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Image Width'
            )
            ->addColumn(
                'product_image_height',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Image Height'
            )
            ->addColumn(
                'product_image_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true, 'default' => null],
                'Product Image CSS'
            )
            ->addColumn(
                'product_text_background',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Background'
            )
            ->addColumn(
                'product_text_comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Comment'
            )
            ->addColumn(
                'product_text_label_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Label Position'
            )
            ->addColumn(
                'product_text_fontsize',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Font Size'
            )
            ->addColumn(
                'product_text_fontcolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Font Color'
            )
            ->addColumn(
                'product_text_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Position'
            )
            ->addColumn(
                'product_text_container',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Container'
            )
            ->addColumn(
                'product_text_width',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Width'
            )
            ->addColumn(
                'product_text_height',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Product Text Height'
            )
            ->addColumn(
                'product_text_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true, 'default' => null],
                'Product Text CSS'
            )
            //IMAGES PRODUCT SETTINGS END

            //IMAGES CATEGORY SETTINGS START
            ->addColumn(
                'category_label_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Category Label Status'
            )
            ->addColumn(
                'category_label_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                10,
                ['nullable' => false],
                'Category Label Type'
            )
            ->addColumn(
                'category_image_label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Image Label'
            )
            ->addColumn(
                'category_image_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Image Position'
            )
            ->addColumn(
                'category_image_container',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Image Container'
            )
            ->addColumn(
                'category_image_width',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Image Width'
            )
            ->addColumn(
                'category_image_height',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Image Height'
            )
            ->addColumn(
                'category_image_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true, 'default' => null],
                'Category Image CSS'
            )
            ->addColumn(
                'category_text_background',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Background'
            )
            ->addColumn(
                'category_text_comment',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Comment'
            )
            ->addColumn(
                'category_text_label_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Label Position'
            )
            ->addColumn(
                'category_text_fontsize',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Font Size'
            )
            ->addColumn(
                'category_text_fontcolor',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Font Color'
            )
            ->addColumn(
                'category_text_position',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Position'
            )
            ->addColumn(
                'category_text_container',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Container'
            )
            ->addColumn(
                'category_text_width',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Width'
            )
            ->addColumn(
                'category_text_height',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Category Text Height'
            )
            ->addColumn(
                'category_text_css',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true, 'default' => null],
                'Category Text CSS'
            )
            //IMAGES CATEGORY SETTINGS END
            //CONDITION SETTINGS START
            ->addColumn(
                'conditions_serialized',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [],
                'Conditions Serialized'
            )
            //CONDITION SETTINGS END
            //DATE RANGE SETTINGS START
            ->addColumn(
                'use_date_range',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Use For Parent'
            )
            ->addColumn(
                'from_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [],
                'From'
            )
            ->addColumn(
                'from_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                ['nullable' => true, 'default' => null],
                'From Time'
            )
            ->addColumn(
                'to_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                null,
                [],
                'To'
            )
            ->addColumn(
                'to_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                ['nullable' => true, 'default' => null],
                'To Time'
            )
            //DATE RANGE SETTINGS END
            //STATE SETTINGS START
            ->addColumn(
                'is_new',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is New'
            )
            ->addColumn(
                'is_on_sale',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Is On Sale'
            )
            //STATE SETTINGS END
            //STOCK SETTINGS START
            ->addColumn(
                'stock_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Stock Status'
            )
            //STOCK SETTINGS END
            //PRICE RANGE SETTINGS START
            ->addColumn(
                'use_price_range',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Use Price Range'
            )
            ->addColumn(
                'by_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                ['nullable' => false],
                'By Price'
            )
            ->addColumn(
                'from_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                ['nullable' => true, 'default' => null],
                'From Price'
            )
            ->addColumn(
                'to_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                55,
                ['nullable' => true, 'default' => null],
                'To Price'
            )
            //PRICE RANGE SETTINGS END
            //CUSTOMER GROUP START
            ->addColumn(
                'use_customer_group',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Use Customer Group'
            )
            ->addColumn(
                'customer_group_ids',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true, 'default' => null],
                'Use Customer Group'
            )
            //CUSTOMER GROUP END
        ;

        $installer->getConnection()->createTable($table);

        $installer->startSetup();
        $table = $installer->getConnection()
            ->newTable($installer->getTable('smart_label_rule_product'))
            //GENERAL SETTINGS START
            ->addColumn(
                'rule_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Rule Id'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Website Id'
            )
            ->addColumn(
                'customer_group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Customer Group Id'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true],
                'Product Id'
            )->addIndex(
                $installer->getIdxName('smart_label_rule_product', ['rule_id']),
                ['rule_id']
            )->addForeignKey(
                $installer->getFkName(
                    'smart_label_rule_product',
                    'rule_id',
                    'smart_label_product',
                    'smart_label_id'
                ),
                'rule_id',
                'smart_label_product',
                'smart_label_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
