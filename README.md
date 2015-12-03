Database data exporter/importer
=============

[![License](https://poser.pugx.org/yuriy-sorokin/database-exporter-importer/license)](https://packagist.org/packages/yuriy-sorokin/database-exporter-importer)
[![Latest Stable Version](https://poser.pugx.org/yuriy-sorokin/database-exporter-importer/v/stable)](https://packagist.org/packages/yuriy-sorokin/database-exporter-importer)
[![Total Downloads](https://poser.pugx.org/yuriy-sorokin/database-exporter-importer/downloads)](https://packagist.org/packages/yuriy-sorokin/database-exporter-importer)

The purpose of the package is to export/import certain data records from a database.
Say, you have a bunch of related DB tables, which represent your user module.
So, you want to copy a user with all the related information to another database, which already has tables created.

It is better to use a DI container to ease the usage of the package.

# Exporting
    <?php
    $connection = new \PDO('mysql:dbname=DATABASE_NAME;host=HOST', 'USER', 'PASSWORD');

    $columnsProvider = new MySqlTableColumnsProvider($connection);
    $tablesProvider = new MySqlTablesProvider($columnsProvider);
    $tablesProvider->setConnection($connection);

    $dataProvider = new MySqlDataProvider($tablesProvider);
    $dataProvider
        ->setConnection($connection)
        ->setForeignValueProvider(new TableForeignKeysValuesProvider())
        ->setPrimaryTableName('maker')
        ->setPrimaryKeyColumn('id')
        ->setPrimaryKey(1);

    $exporter = new JsonDataExporter();
    $data = $exporter
        ->setColumnsExporter(new JsonTableColumnsExporter())
        ->setDataRowsExporter(new JsonTableDataRowsExporter())
        ->setDataProvider($dataProvider)
        ->getData();
        
Finally, this `$data` variable contains a JSON string, which is ready to be imported.
Pay attention that auto increment values of the root table are reset while importing.
 
# Importing
    <?php
    $connection = new \PDO('mysql:dbname=DATABASE_NAME;host=HOST', 'USER', 'PASSWORD');
    
    $dataParser = new JsonDataParser();
    $dataParser
        ->setColumnsCreator(new JsonColumnsCreator())
        ->setDataRowsCreator(new JsonDataRowsCreator())
        ->setData($data);

    $subject = new MySqlDataImporter();
    $subject
        ->setConnection($connection)
        ->setDataParser($dataParser)
        ->setObserver(new AutoIncrementObserver(new ForeignKeyColumnsFinder()))
        ->setAutoIncrementFinder(new AutoIncrementTableColumnFinder())
        ->import();

Currently it supports only MySQL database and JSON export/import structure.

The test database dump is located in `dump.sql.gz`.
