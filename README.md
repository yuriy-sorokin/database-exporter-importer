Database data exporter/importer
=============

This package is used to export/import database data to/from different formats. Currently it supports only MySQL database and JSON export/import structure.

In order to use it, please, modify the database connection parameters in MySqlConnectionCreator class.
There are two such classes, one is used when running tests.

The test database dump is located in `dump.sql`.
