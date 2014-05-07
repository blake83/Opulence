<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Defines the interface for login credentials factories to implement
 */
namespace RamODev\Application\Shared\Users\Authentication\Credentials\Factories;
use RamODev\Application\Shared\Users\Authentication\Credentials;

interface ILoginCredentialsFactory
{
    /**
     * Creates credentials for the input user
     *
     * @param int $userId The Id of the user whose credentials these are
     * @param \DateTime $validFrom The valid-from time
     * @param \DateTime $validTo The valid-to time
     * @return Credentials\ILoginCredentials
     */
    public function createLoginCredentials($userId, \DateTime $validFrom, \DateTime $validTo);
} 