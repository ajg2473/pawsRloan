<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 10:42 AM
 */

namespace Bolzen\Core\Database;

interface DatabaseInterface
{


    /**
     * Take a given sql and execute the sql statement
     * @param string $sql - the given sql statement
     * @param array $bindings - the binding variables for the sql parameters
     * @return \PDOStatement - PDOStatement of the result
     */
    public function genericSqlQueryBuilder(string $sql, array $bindings = array()):\PDOStatement;

    /**
     * Returns the PDO instance of the database
     * @return \PDO
     */
    public function getPDO():\PDO;

    /**
     * Perform a select statement on a given table according to the value supplied to the parameters
     * @param string $table - the table to perform select on
     * @param string $where - the where clause example "id=?"
     * @param array $bindings - the binding for the where clause(s)
     * @param string $columns - the columns to select from
     * @return null|\PDOStatement - return PDOStatement on successful otherwise a null is return
     */
    public function select(
        string $table,
        string $where = "",
        array $bindings = array(),
        string $columns = ""
    ):?\PDOStatement;

    /**
     * Check if a given sql is successful
     * @param string $sql - the sql statement
     * @param array $bindings - the binding statement for the sql
     * @return bool - true if success and false otherwise
     */
    public function isSqlQuerySuccessful(string $sql, array $bindings = array()):bool;

    /**
     * Perform insert sql statement
     * @param string $table - the table to insert into
     * @param string $columns - the columns of the table
     * @param string $values - the values, parameterize formats are suggested eg ?, :id and others
     * @param array $bindings - the bindings for the values
     * @return bool - true if success and false otherwise
     */
    public function insert(string $table, string $columns, string $values, array $bindings):bool;

    /**
     * Perform insert sql statement
     * @param string $table - the table to update
     * @param string $set - the column to set along with parameterize value such as id=?
     * @param string $where - the where clause on which value to update
     * @param array $bindings - the bindings for the parameterize values
     * @return bool - true if success and false otherwise
     */
    public function update(string $table, string $set, string $where, array $bindings):bool;

    /**
     * Perform delete statement
     * @param string $table - the table to delete from
     * @param string $where - the where clause to delete
     * @param array $bindings - the parameterize value for the where clause
     * @return bool - true if success and false otherwise
     */
    public function delete(string $table, string $where, array $bindings):bool;

    /**
     * Commit the change to the database
     * @return bool - true if success and false otherwise
     */
    public function commit():bool;

    /**
     * Rollback the uncommited change to the database
     * @return bool - true if success and false otherwise
     */
    public function rollback():bool;

    /**
     * Set the database transaction.
     */
    public function beginTransaction():void;

    /**
     * set the transcation mode
     * @param bool $enabled - true to enable transaction and false to not enable
     * transaction. By default, transaction is enabled
     */
    public function enableTransaction(bool $enabled = true):void;

    /**
     * @return string to get a database name
     */
    public function getDatabaseName():string;

}