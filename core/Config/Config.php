<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/13/18
 * Time: 3:34 PM
 */

namespace Bolzen\Core\Config;

class Config implements ConfigInterface
{
    private $environment;
    private $debug;
    private $server;
    private $database;
    private $config;
    private $isDatabaseEnabled;

    public function __construct()
    {
        $this->config = include_once __DIR__ . '/../../config/config.php';
        //loading the environment and server info
        $this->environment();
        $this->readServerInfo();

        //do we need to load the database environment?
        if ($this->isDatabaseEnabled()) {
            $this->readDatabaseInfo();
        }
    }

    /**
     * This function returns the current environment of the app such as development, testing and development
     * @return string the current stage environment
     */
    public function environment(): string
    {
        if (!$this->environment) {
            if (!isset($this->config['configureFor'])) {
                throw new \InvalidArgumentException("configureFor parameter is missing from 
            the config array in config.php");
            }

            $allowed = array("dev","test","prod");
            $this->environment = $this->config['configureFor'];

            if (!in_array($this->environment, $allowed)) {
                throw new \InvalidArgumentException("Illegal parameter supplied to configureFor");
            }
        }
        return $this->environment;
    }

    /**
     * read and parse the corresponding server info from the config
     * @return array
     */
    private function readServerInfo():array
    {
        $targetServer = $this->environment."Server";

        //have not been initalized
        if (!$this->server) {
            //is the server parameter in the config?
            if (!isset($this->config[$targetServer])) {
                throw new \InvalidArgumentException("parameter $targetServer is missing from config.php");
            }

            $this->server = $this->config[$targetServer];

            //is the directory missing?
            if (!isset($this->server['projectDirectory'])) {
                throw new \InvalidArgumentException("the parameter projectDirectory is missing 
                from $targetServer in config.php");
            }

            //is the scheme missing?
            if (!isset($this->server['scheme'])) {
                throw new \InvalidArgumentException("The parameter scheme is missing from $targetServer in config.php");
            }

            //ensure we have a valid scheme type
            $allowed = array("https","http");

            if (!in_array($this->server['scheme'], $allowed)) {
                throw new \InvalidArgumentException("Invalid scheme type provided in $targetServer. 
                Current supported schemes are http and https");
            }

            //is the host missing?
            if (!isset($this->server['host'])) {
                throw new \InvalidArgumentException("The parameter host is missing from $targetServer in config.php");
            }
        }

        return $this->server;
    }

    /**
     * The hosting scheme for the app such as http or https
     * @return string return the current scheme
     */
    public function hostingScheme(): string
    {
        return $this->server['scheme'];
    }

    /**
     * The hosting host such as localhost.
     * @return string the host
     */
    public function hostingHost(): string
    {
        return $this->server['host'];
    }

    /**
     * This function returns the app's project directory
     * @return string the project directory
     */
    public function getProjectDirectory(): string
    {
        return $this->server['projectDirectory'];
    }

    /**
     * Returns a boolean expression as to whether the debug is enabled.
     * @return bool true if debug is enabled. False otherwise
     */
    public function isDebugEnabled(): bool
    {

        if (!$this->debug) {
            if (!isset($this->config['debug'])) {
                throw new \InvalidArgumentException("The parameter debug is missing from config.php");
            }

            //is an instance of boolean?
            $this->debug = $this->config['debug'];
            if (!is_bool($this->debug)) {
                throw new \InvalidArgumentException("The parameter debug must be a boolean in config.php");
            }
        }

        return $this->debug;
    }

    /**
     * Returns a boolean expression on whether the app plan to use database
     * @return bool true if the app will use database, false otherwise
     */
    public function isDatabaseEnabled(): bool
    {
        if (!$this->isDatabaseEnabled) {
            if (!isset($this->config['useDatabase'])) {
                throw new \InvalidArgumentException("The parameter useDatabase is missing from config.php");
            }

            //must be a boolean expression
            $this->isDatabaseEnabled = $this->config['useDatabase'];
            if (!is_bool($this->isDatabaseEnabled)) {
                throw new \InvalidArgumentException("The parameter useDatabase must be a boolean expression");
            }
        }

        return $this->isDatabaseEnabled;
    }


    /**
     * @return array a list of host, username, password, and database
     */
    public function readDatabaseInfo():array
    {
        if (!$this->database) {
            $targetDatabase = $this->environment."Database";

            //make sure the parameter exist
            if (!isset($this->config[$targetDatabase])) {
                throw new \InvalidArgumentException("The parameter $targetDatabase is missing from the config.php");
            }

            $this->database = $this->config[$targetDatabase];

            //can we get the prefix?
            if (!isset($this->database['prefix'])) {
                throw new \InvalidArgumentException("The parameter prefix is 
                missing from $targetDatabase in config.php");
            }

            //can we get the host?
            if (!isset($this->database['host'])) {
                throw new \InvalidArgumentException("The parameter host is missing from $targetDatabase in config.php");
            }

            //can we get the username?
            if (!isset($this->database['username'])) {
                throw new \InvalidArgumentException("The parameter username is missing 
                from $targetDatabase in config.php");
            }

            //can we get the password?
            if (!isset($this->database['password'])) {
                throw new \InvalidArgumentException("The parameter password is missing from 
                $targetDatabase in config.php");
            }

            //can we get the database name?
            if (!isset($this->database['database'])) {
                throw new \InvalidArgumentException("The parameter database is missing from 
                $targetDatabase in config.php");
            }
        }

        return $this->database;
    }

    /**
     * Returns the name of the database
     * @return string return the name of the database
     */
    public function databaseName(): string
    {
        $this->enableDatabaseFirst();
        return $this->database['database'];
    }

    /**
     * Get the name of the database's Dsn
     * @return string return the database dsn
     */
    public function databaseDsn(): string
    {
        $this->enableDatabaseFirst();
        //$dsn = 'mysql:host=localhost;dbname=testdb';
        return $this->databasePrefix().":host=".$this->databaseHost().";dbname=".$this->databaseName().";charset=utf8";
    }

    /**
     * Returns the name of the database's username
     * @return string database username
     */
    public function databaseUsername(): string
    {
        $this->enableDatabaseFirst();
        return $this->database['username'];
    }

    /**
     * Get Database and enable it
     */
    public function enableDatabaseFirst()
    {
        if (!$this->isDatabaseEnabled) {
            throw new \InvalidArgumentException("you must set useDatabase to true to use database related features");
        }
    }
    /**
     * Get the database password
     * @return string database password
     */
    public function databasePassword(): string
    {
        $this->enableDatabaseFirst();
        return $this->database['password'];
    }

    /**
     * Get the name of the database's prefix
     * @return string database prefix
     */
    public function databasePrefix(): string
    {
        $this->enableDatabaseFirst();
        return $this->database['prefix'];
    }

    /**
     * Get the name of the database's host
     * @return string the database's host
     */
    public function databaseHost(): string
    {
        $this->enableDatabaseFirst();
        return $this->database['host'];
    }

    /**
     * @return string with a url
     */
    public function getBaseUrl():string
    {
        //https://localhost/projectDirectory

        return $this->hostingScheme()."://".$this->hostingHost()."/".$this->getProjectDirectory()."/";
    }
}