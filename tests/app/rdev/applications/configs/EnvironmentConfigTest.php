<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Tests the environment config
 */
namespace RDev\Applications\Configs;

class EnvironmentConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests a config with a callback
     */
    public function testConfigWithCallback()
    {
        $configArray = [
            "names" => [
                function ()
                {
                    return "staging";
                }
            ]
        ];
        $config = new EnvironmentConfig($configArray);
        $this->assertEquals($configArray, $config->getArrayCopy());
    }

    /**
     * Tests a config with a custom, although invalid, type
     */
    public function testConfigWithInvalidHostOptionType()
    {
        $this->setExpectedException("\\RuntimeException");
        $configArray = [
            "names" => [
                "staging" => [
                    ["type" => "doesnotexist", "value" => "foo"],
                    "192.168.1.1"
                ]
            ]
        ];
        new EnvironmentConfig($configArray);
    }

    /**
     * Tests a config with a regex
     */
    public function testConfigWithRegex()
    {
        $configArray = [
            "names" => [
                "staging" => [
                    ["type" => "regex", "value" => "/^192\.168\.*$/"],
                    "192.168.1.1"
                ]
            ]
        ];
        $config = new EnvironmentConfig($configArray);
        $this->assertEquals($configArray, $config->getArrayCopy());
    }

    /**
     * Tests not setting anything
     */
    public function testNotSettingAnything()
    {
        $config = new EnvironmentConfig([]);
        $this->assertEquals([], $config->getArrayCopy());
    }

    /**
     * Tests passing an invalid development option
     */
    public function testPassingInvalidDevelopmentOption()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "development" => 1
            ]
        ]);
    }

    /**
     * Tests passing an invalid list of development hosts
     */
    public function testPassingInvalidListOfDevelopmentHosts()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "development" => [1, 2]
            ]
        ]);
    }

    /**
     * Tests passing an invalid list of production hosts
     */
    public function testPassingInvalidListOfProductionHosts()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "production" => [1, 2]
            ]
        ]);
    }

    /**
     * Tests passing an invalid list of staging hosts
     */
    public function testPassingInvalidListOfStagingHosts()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "staging" => [1, 2]
            ]
        ]);
    }

    /**
     * Tests passing an invalid list of testing hosts
     */
    public function testPassingInvalidListOfTestingHosts()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "testing" => [1, 2]
            ]
        ]);
    }

    /**
     * Tests passing an invalid production option
     */
    public function testPassingInvalidProductionOption()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "production" => 1
            ]
        ]);
    }

    /**
     * Tests passing an invalid staging option
     */
    public function testPassingInvalidStagingOption()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "staging" => 1
            ]
        ]);
    }

    /**
     * Tests passing an invalid testing option
     */
    public function testPassingInvalidTestingOption()
    {
        $this->setExpectedException("\\RuntimeException");
        new EnvironmentConfig([
            "names" => [
                "testing" => 1
            ]
        ]);
    }

    /**
     * Tests passing valid development options as an array
     */
    public function testPassingValidDevelopmentOptionsAsArray()
    {
        $configArray = [
            "names" => [
                "development" => ["foo"]
            ]
        ];
        $config = new EnvironmentConfig($configArray);
        $this->assertEquals($configArray, $config->getArrayCopy());
    }

    /**
     * Tests passing valid development options as a string
     */
    public function testPassingValidDevelopmentOptionsAsString()
    {
        $config = new EnvironmentConfig([
            "names" => [
                "development" => "foo"
            ]
        ]);
        $this->assertEquals([
            "names" => [
                "development" => ["foo"]
            ]
        ], $config->getArrayCopy());
    }

    /**
     * Tests passing valid production options as an array
     */
    public function testPassingValidProductionOptionsAsArray()
    {
        $configArray = [
            "names" => [
                "production" => ["foo"]
            ]
        ];
        $config = new EnvironmentConfig($configArray);
        $this->assertEquals($configArray, $config->getArrayCopy());
    }

    /**
     * Tests passing valid production options as a string
     */
    public function testPassingValidProductionOptionsAsString()
    {
        $config = new EnvironmentConfig([
            "names" => [
                "production" => "foo"
            ]
        ]);
        $this->assertEquals([
            "names" => [
                "production" => ["foo"]
            ]
        ], $config->getArrayCopy());
    }

    /**
     * Tests passing valid staging options as an array
     */
    public function testPassingValidStagingOptionsAsArray()
    {
        $configArray = [
            "names" => [
                "staging" => ["foo"]
            ]
        ];
        $config = new EnvironmentConfig($configArray);
        $this->assertEquals($configArray, $config->getArrayCopy());
    }

    /**
     * Tests passing valid staging options as a string
     */
    public function testPassingValidStagingOptionsAsString()
    {
        $config = new EnvironmentConfig([
            "names" => [
                "staging" => "foo"
            ]
        ]);
        $this->assertEquals([
            "names" => [
                "staging" => ["foo"]
            ]
        ], $config->getArrayCopy());
    }

    /**
     * Tests passing valid testing options as an array
     */
    public function testPassingValidTestingOptionsAsArray()
    {
        $configArray = [
            "names" => [
                "testing" => ["foo"]
            ]
        ];
        $config = new EnvironmentConfig($configArray);
        $this->assertEquals($configArray, $config->getArrayCopy());
    }

    /**
     * Tests passing valid testing options as a string
     */
    public function testPassingValidTestingOptionsAsString()
    {
        $config = new EnvironmentConfig([
            "names" => [
                "testing" => "foo"
            ]
        ]);
        $this->assertEquals([
            "names" => [
                "testing" => ["foo"]
            ]
        ], $config->getArrayCopy());
    }
} 