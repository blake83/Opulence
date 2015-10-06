<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Defines the Bcrypt cryptographic hasher
 */
namespace Opulence\Cryptography\Hashing;

class BcryptHasher extends Hasher
{
    /** The default cost used by this hasher */
    const DEFAULT_COST = 10;

    /**
     * @inheritdoc
     */
    public function hash($unhashedValue, array $options = [], $pepper = "")
    {
        if (!isset($options["cost"])) {
            $options["cost"] = self::DEFAULT_COST;
        }

        return parent::hash($unhashedValue, $options, $pepper);
    }

    /**
     * @inheritdoc
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        if (!isset($options["cost"])) {
            $options["cost"] = self::DEFAULT_COST;
        }

        return parent::needsRehash($hashedValue, $options);
    }

    /**
     * @inheritdoc
     */
    protected function setHashAlgorithm()
    {
        $this->hashAlgorithm = PASSWORD_BCRYPT;
    }
} 