<?php
/**
 * Copyright (C) 2014 David Young
 * 
 * Defines an application environment
 */
namespace RDev\Applications;

class Environment
{
    /** The production environment */
    const PRODUCTION = "production";
    /** The staging environment */
    const STAGING = "staging";
    /** The testing environment */
    const TESTING = "testing";
    /** The development environment */
    const DEVELOPMENT = "development";

    /** @var string The name of the environment */
    private $name = "";

    /**
     * @param IEnvironmentDetector $detector The environment detector to use
     */
    public function __construct(IEnvironmentDetector $detector)
    {
        $this->setName($detector->detect());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the value of an environment variable
     *
     * @param string $name The name of the environment variable to get
     * @return string|null The value of the environment value if one was set, otherwise null
     */
    public function getVar($name)
    {
        $value = getenv($name);

        if($value === false)
        {
            return null;
        }

        return $value;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets an environment variable
     *
     * @param string $name The name of the environment variable to set
     * @param mixed $value The value
     */
    public function setVar($name, $value)
    {
        putenv($name . "=" . $value);
    }
}