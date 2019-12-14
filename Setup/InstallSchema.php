<?php
namespace FME\Contactus\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{  
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context){
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('fme_contactus')
            )->addColumn(
            'contact_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
            )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Name'
             )->addColumn(
            'email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'email'
             )->addColumn(
            'phone',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Phone'
             )->addColumn(
            'subject',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Subject'
            )->addColumn(
            'message',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Message'
            )->addColumn(
            'reply',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'Reply'
            )->addColumn(
            'country',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Country'
            )->addColumn(
            'ip',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Ip'
            )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'unsigned' => true, 'default' => '1'],
            'Status'
            )->addColumn(
            'created_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Created Time'
            )->addColumn(
            'reply_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Reply Time'
            );
             $installer->getConnection()->createTable($table);
             $table = $installer->getConnection()->newTable(
                $installer->getTable('fme_contactus_custom_form')
                )->addColumn(
                'field_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'ID'
                )->addColumn(
                'label',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                [],
                'Label'
                )->addColumn(
                'placeholder',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                100,
                [],
                'Placeholder'
                )->addColumn(
                'value',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Value'
                )->addColumn(
                'required',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Required'
                )->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Type'
                )->addColumn(
                'sortorder',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                20,
                [],
                'Sort Order'
                )->addColumn(
                'validation',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Validation'
                )->addColumn(
                'file_size',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'File Size'
                )->addColumn(
                'file_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'File Type' 
                )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Status'
                )->addColumn(
                'created_time',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created Time'
                );
                $installer->getConnection()->createTable($table);
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('fme_contactus_custom_form_options')
                    )->addColumn(
                    'option_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'Option ID'
                    )->addColumn(
                    'field_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    [ 
                    'nullable' => false
                    ],
                    'Field ID'
                    )->addIndex(
                    $installer->getIdxName('fme_contactus_custom_form_options', ['field_id']),
                    ['field_id']
                    )->addForeignKey(
                    $installer->getFkName('fme_contactus_custom_form_options', 'field_id', 'fme_contactus_custom_form', 'field_id'),
                    'field_id',
                    $installer->getTable('fme_contactus_custom_form'),
                    'field_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )->addColumn(
                    'value',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    50,
                    [],
                    'Value'
                    )->setComment('Form To Options Link Table'); 
                    $installer->getConnection()->createTable($table);
                    $table = $installer->getConnection()->newTable(
                        $installer->getTable('fme_contactus_form_save')
                        )->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'nullable' => false, 'primary' => true],
                        'ID'
                        )->addColumn(
                        'contact_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [ 
                        'nullable' => false
                        ],
                        'Contact ID'
                        )->addIndex(
                        $installer->getIdxName('fme_contactus_form_save', ['contact_id']),
                        ['contact_id']
                        )->addForeignKey(
                        $installer->getFkName('fme_contactus_form_save', 'contact_id', 'fme_contactus', 'contact_id'),
                        'contact_id',
                        $installer->getTable('fme_contactus'),
                        'contact_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                        )->addColumn(
                        'field_label',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Field Label'
                        )->addColumn(
                        'field_value',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Field Value'
                        );
                        $installer->getConnection()->createTable($table);
                        $installer->endSetup();
    }
}
