# CpImport Bundle

[**MariaDb ColumnStore**](https://mariadb.com/fr/node/819) is a very powerful, column-oriented MySQL storage, powerful for analytics and Big Data.

It has the drawback of extremely slow write operations, but comes with `cpimport`, a binary that can load CSV files with millions of rows into the table of your choice, in a few seconds.

This Symfony bundle enables a file watcher that will look into a directory, pick up new CSV files when they come, run `cpimport` and delete them.

> [!IMPORTANT]  
> This repository is no longer maintained and may be removed in a near future. You may consider creating a fork if you still require it.

## Installation

> composer require bentools/cpimport-bundle:1.0.x-dev

## Configuration

Add the bundle to your kernel. Then, map your directories to database/tables like this:

```yaml
# config/packages/cpimport.yaml (or app/config.yml in Symfony 3)

cpimport:
    cpimport_bin: /usr/local/mariadb/columnstore/bin/cpimport
    watch:
        /path/to/csv_sales:
            database: my_shop
            table: sales_day
            options:
                delimiter: ','
                enclosure: '"'
                timeout: 60
```

## Usage

To automatically process new files in configured directories:
```bash
php bin/console cpimport:watch
```


To manually process a CSV file through `cpimport`:
```bash
php bin/console cpimport:run my_shop sales_day path/to/file.csv --delimiter=','
```
