<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/13/18
 * Time: 3:04 PM
 */

namespace Bolzen\Core\Config;

interface ConfigInterface
{
    /**
     * This function returns the current environment of the app such as development, testing and development
     * @return string the current stage environment
     */
    public function environment():string;

    /**
     * The hosting scheme for the app such as http or https
     * @return string return the current scheme
     */
    public function hostingScheme():string;

    /**
     * The hosting host such as localhost.
     * @return string the host
     */
    public function hostingHost():string;

    /**
     * This function returns the app's project directory
     * @return string the project directory
     */
    public function getProjectDirectory(): string;

    /**
     * Returns a boolean expression as to whether the debug is enabled.
     * @return bool true if debug is enabled. False otherwise
     */
    public function isDebugEnabled():bool;

    /**
     * Returns a boolean expression on whether the app plan to use database
     * @return bool true if the app will use database, false otherwise
     */
    public function isDatabaseEnabled():bool;

    /**
     * Returns the name of the database
     * @return string return the name of the database
     */
    public function databaseName():string;

    /**
     * Get the name of the database's Dsn
     * @return string return the database dsn
     */
    public function databaseDsn():string;

    /**
     * Returns the name of the database's username
     * @return string database username
     */
    public function databaseUsername():string;

    /**
     * Get the database password
     * @return string database password
     */
    public function databasePassword():string;

    /**
     * Get the name of the database's prefix
     * @return string database prefix
     */
    public function databasePrefix():string;

    /**
     * Get the name of the database's host
     * @return string the database's host
     */
    public function databaseHost():string;

    /**
     * @return string get base url
     */
    public function getBaseUrl():string;
}