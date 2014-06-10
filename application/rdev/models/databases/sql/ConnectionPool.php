<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Defines a database connection pool
 */
namespace RDev\Models\Databases\SQL;

abstract class ConnectionPool
{
    /**
     * The configuration that holds data about the master and slave servers as well as any connections made to them
     * The "custom" key holds a list of any servers that have been passed in as preferred servers when attempting to get
     * a connection
     *
     * @var array
     */
    protected $config = [
        "master" => ["server" => null, "connection" => null],
        "custom" => []
    ];
    /** @var IConnectionFactory The factory to use to create database connections */
    protected $connectionFactory = null;
    /** @var IConnection|null The connection to use for read queries */
    protected $readConnection = null;
    /** @var IConnection|null The connection to use for write queries */
    protected $writeConnection = null;

    /**
     * @param IConnectionFactory $connectionFactory The factory to use to create database connections
     */
    public function __construct(IConnectionFactory $connectionFactory)
    {
        $this->setConnectionFactory($connectionFactory);
    }

    /**
     * @return Server
     */
    public function getMaster()
    {
        return $this->config["master"]["server"];
    }

    /**
     * Gets the connection used for read queries
     *
     * @param Server $preferredServer The preferred server to use
     * @return IConnection The connection to use for reads
     */
    public function getReadConnection(Server $preferredServer = null)
    {
        if($preferredServer !== null)
        {
            $this->addServer("custom", $preferredServer);
            $this->setReadConnection($preferredServer);
        }
        elseif($this->readConnection == null)
        {
            $this->setReadConnection();
        }

        return $this->readConnection;
    }

    /**
     * Gets the connection used for write queries
     *
     * @param Server $preferredServer The preferred server to use
     * @return IConnection The connection to use for writes
     */
    public function getWriteConnection(Server $preferredServer = null)
    {
        if($preferredServer != null)
        {
            $this->addServer("custom", $preferredServer);
            $this->setWriteConnection($preferredServer);
        }
        elseif($this->writeConnection == null)
        {
            $this->setWriteConnection();
        }

        return $this->writeConnection;
    }

    /**
     * @param IConnectionFactory $connectionFactory
     */
    public function setConnectionFactory(IConnectionFactory $connectionFactory)
    {
        $this->connectionFactory = $connectionFactory;
    }

    /**
     * @param Server $master
     */
    public function setMaster(Server $master)
    {
        $this->addServer("master", $master);
    }

    /**
     * Sets the connection to use for read queries
     *
     * @param Server $preferredServer The preferred server to connect to
     */
    abstract protected function setReadConnection(Server $preferredServer = null);

    /**
     * Sets the connection to use for write queries
     *
     * @param Server $preferredServer The preferred server to connect to
     */
    abstract protected function setWriteConnection(Server $preferredServer = null);

    /**
     * Adds a server to our list of servers
     *
     * @param string $type The type of server we're trying to add, eg "master", "custom"
     * @param Server $server The server to add
     */
    protected function addServer($type, Server $server)
    {
        switch($type)
        {
            case "master":
                $this->config["master"] = ["server" => $server, "connection" => null];
                break;
            default:
                $serverHashId = spl_object_hash($server);

                if(!isset($this->config[$type][$serverHashId]))
                {
                    $this->config[$type][$serverHashId] = ["server" => $server, "connection" => null];
                }

                break;
        }
    }

    /**
     * Gets a connection to the input server
     *
     * @param string $type The type of server we're trying to connect to, eg "master", "custom"
     * @param Server $server The server we want to connect to
     * @return IConnection The connection to the server
     */
    protected function getConnection($type, Server $server)
    {
        switch($type)
        {
            case "master":
                if($this->config["master"]["connection"] == null)
                {
                    $this->config["master"]["connection"] = $this->connectionFactory->create($server);
                }

                return $this->config["master"]["connection"];
            default:
                $serverHashId = spl_object_hash($server);

                if($this->config[$type][$serverHashId]["connection"] == null)
                {
                    $this->config[$type][$serverHashId]["connection"] = $this->connectionFactory->create($server);
                }

                return $this->config[$type][$serverHashId]["connection"];
        }
    }
} 