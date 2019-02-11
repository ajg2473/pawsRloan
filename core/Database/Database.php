<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/18/18
 * Time: 11:37 AM
 */

namespace Bolzen\Core\Database;

use Bolzen\Core\Config\ConfigInterface;

class Database implements DatabaseInterface
{
    /**
     * @var \PDO - the PDO instance
     */
    private $pdo;

    /**
     * @var ConfigInterface - the config instance
     */
    private $config;

    /**
     * @var bool - a boolean statement on whether there are any pending transaction not yet commit or rollback
     */
    private $pendingTransaction = false;

    /**
     * @var bool - a boolean statement on whether the database should make the use of transaction
     */
    private $transactionEnabled = true;

    /**
     * Database constructor - takes the Config interace and initalize the config
     * as well as established connection to the database via PDo
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->pdo = $this->connect();
    }

    /**
     * connect to the database using the supplied information
     * from the config and return a pdo instance
     * @return \PDO - the pdo instance of the connection
     */
    private function connect():\PDO
    {

        $conn = new \PDO(
            $this->config->databaseDsn(),
            $this->config->databaseUsername(),
            $this->config->databasePassword()
        );

        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

        return $conn;
    }

    /**
     * Take a given sql and execute the sql statement
     * @param string $sql - the given sql statement
     * @param array $bindings - the binding variables for the sql parameters
     * @return \PDOStatement - PDOStatement of the result
     */
    public function genericSqlQueryBuilder(string $sql, array $bindings = array()): \PDOStatement
    {

        //$sql = $this->pdo->quote($sql,\PDO::PARAM_STR);
        $this->beginTransaction();
        $statement = $this->pdo->prepare($sql);
        $statement->execute($bindings);

        return $statement;
    }

    /**
     * Returns the PDO instance of the database
     * @return \PDO
     */
    public function getPDO(): \PDO
    {
        return $this->pdo;
    }

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
        string $columns = "*"
    ): ?\PDOStatement {

        $sql = "SELECT ".$columns." FROM ".$table;

        if (!empty($where)) {
            $sql.= " WHERE ".$where;
        }


        $result = $this->genericSqlQueryBuilder($sql, $bindings);

        if ($result->rowCount()===0) {
            return null;
        }

        return $result;
    }

    /**
     * Check if a given sql is successful
     * @param string $sql - the sql statement
     * @param array $bindings - the binding statement for the sql
     * @return bool - true if success and false otherwise
     */
    public function isSqlQuerySuccessful(string $sql, array $bindings = array()): bool
    {
        return $this->genericSqlQueryBuilder($sql, $bindings)->rowCount() > 0;
    }

    /**
     * Perform insert sql statement
     * @param string $table - the table to insert into
     * @param string $columns - the columns of the table
     * @param string $values - the values, parameterize formats are suggested eg ?, :id and others
     * @param array $bindings - the bindings for the values
     * @return bool - true if success and false otherwise
     */
    public function insert(string $table, string $columns, string $values, array $bindings): bool
    {
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->isSqlQuerySuccessful($sql, $bindings);
    }

    /**
     * Perform insert sql statement
     * @param string $table - the table to update
     * @param string $set - the column to set along with parameterize value such as id=?
     * @param string $where - the where clause on which value to update
     * @param array $bindings - the bindings for the parameterize values
     * @return bool - true if success and false otherwise
     */
    public function update(string $table, string $set, string $where, array $bindings): bool
    {
        $sql = "UPDATE $table SET $set WHERE $where";

        return $this->isSqlQuerySuccessful($sql, $bindings);
    }

    /**
     * Perform delete statement
     * @param string $table - the table to delete from
     * @param string $where - the where clause to delete
     * @param array $bindings - the parameterize value for the where clause
     * @return bool - true if success and false otherwise
     */
    public function delete(string $table, string $where, array $bindings): bool
    {
        $sql = "DELETE FROM $table WHERE $where";

        return $this->isSqlQuerySuccessful($sql, $bindings);
    }

    /**
     * Commit the change to the database
     * @return bool - true if success and false otherwise
     */
    public function commit(): bool
    {
        if ($this->pendingTransaction) {
            $this->pendingTransaction = false;

            return $this->pdo->commit();
        }

        return false;
    }

    /**
     * Rollback the uncommited change to the database
     * @return bool - true if success and false otherwise
     */
    public function rollback(): bool
    {
        if ($this->pendingTransaction) {
            $this->pendingTransaction = false;
            return $this->pdo->rollBack();
        }

        return false;
    }

    /**
     * Set the database transaction.
     */
    public function beginTransaction(): void
    {
        if ($this->transactionEnabled && !$this->pendingTransaction) {
            $this->pdo->beginTransaction();
            $this->pendingTransaction = true;
        }
    }

    /**
     * set the transcation mode
     * @param bool $enabled - true to enable transaction and false to not enable
     * transaction. By default, transaction is enabled
     */
    public function enableTransaction(bool $enabled = true): void
    {
        $this->transactionEnabled = $enabled;
    }

    /**
     * @return string with database name
     */
    public function getDatabaseName():string
    {
        return $this->config->databaseName();
    }
}
