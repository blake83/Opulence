<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Defines a base request parser
 */
namespace Opulence\Console\Requests\Parsers;

use Opulence\Console\Requests\Request;
use RuntimeException;

abstract class Parser implements IParser
{
    /**
     * Parses an argument value
     *
     * @param string $token The token to parse
     * @return string The parsed argument
     */
    protected function parseArgument($token)
    {
        return $this->trimQuotes($token);
    }

    /**
     * Parses a long option token and returns an array of data
     *
     * @param string $token The token to parse
     * @param array $remainingTokens The list of remaining tokens
     * @return array The name of the option mapped to its value
     * @throws RuntimeException Thrown if the option could not be parsed
     */
    protected function parseLongOption($token, array &$remainingTokens)
    {
        if (mb_substr($token, 0, 2) !== "--") {
            throw new RuntimeException("Invalid long option \"$token\"");
        }

        // Trim the "--"
        $option = mb_substr($token, 2);

        if (mb_strpos($option, "=") === false) {
            /**
             * The option is either of the form "--foo" or "--foo bar" or "--foo -b" or "--foo --bar"
             * So, we need to determine if the option has a value
             */
            $nextToken = array_shift($remainingTokens);

            // Check if the next token is also an option
            if (mb_substr($nextToken, 0, 1) == "-" || empty($nextToken)) {
                // The option must have not had a value, so put the next token back
                array_unshift($remainingTokens, $nextToken);

                return [$option, null];
            }

            // Make it "--foo=bar"
            $option .= "=" . $nextToken;
        }

        list($name, $value) = explode("=", $option);
        $value = $this->trimQuotes($value);

        return [$name, $value];
    }

    /**
     * Parses a short option token and returns an array of data
     *
     * @param string $token The token to parse
     * @return array The name of the option mapped to its value
     * @throws RuntimeException Thrown if the option could not be parsed
     */
    protected function parseShortOption($token)
    {
        if (mb_substr($token, 0, 1) !== "-") {
            throw new RuntimeException("Invalid short option \"$token\"");
        }

        // Trim the "-"
        $token = mb_substr($token, 1);

        $options = [];

        // Each character in a short option is an option
        for ($charIter = 0;$charIter < mb_strlen($token);$charIter++) {
            $options[] = [$token[$charIter], null];
        }

        return $options;
    }

    /**
     * Parses a list of tokens into a request
     *
     * @param array $tokens The tokens to parse
     * @return Request The parsed request
     */
    protected function parseTokens(array $tokens)
    {
        $request = new Request();
        $hasParsedCommandName = false;

        while ($token = array_shift($tokens)) {
            if (mb_substr($token, 0, 2) == "--") {
                $option = $this->parseLongOption($token, $tokens);
                $request->addOptionValue($option[0], $option[1]);
            }elseif (mb_substr($token, 0, 1) == "-") {
                $options = $this->parseShortOption($token);

                foreach ($options as $option) {
                    $request->addOptionValue($option[0], $option[1]);
                }
            }else {
                if (!$hasParsedCommandName) {
                    // We consider this to be the command name
                    $request->setCommandName($token);
                    $hasParsedCommandName = true;
                }else {
                    // We consider this to be an argument
                    $request->addArgumentValue($this->parseArgument($token));
                }
            }
        }

        return $request;
    }

    /**
     * Trims the outer-most quotes from a token
     *
     * @param string $token Trims quotes off of a token
     * @return string The trimmed token
     */
    protected function trimQuotes($token)
    {
        // Trim any quotes
        if (($firstValueChar = mb_substr($token, 0, 1)) == mb_substr($token, -1)) {
            if ($firstValueChar == "'") {
                $token = trim($token, "'");
            }elseif ($firstValueChar == '"') {
                $token = trim($token, '"');
            }
        }

        return $token;
    }
}