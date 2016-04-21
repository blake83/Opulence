<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2016 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Ioc;

/**
 * Defines the interface for dependency injection containers to implement
 */
interface INewContainer
{
    /**
     * Binds a factory that will return a concrete instance of the interface
     *
     * @param string|array $interfaces The interface or interfaces to bind to
     * @param callable $factory The factory to bind
     */
    public function bindFactory($interfaces, callable $factory);

    /**
     * Binds a concrete instance to the interface
     *
     * @param string|array $interfaces The interface or interfaces to bind to
     * @param object $instance The instance to bind
     */
    public function bindInstance($interfaces, $instance);

    /**
     * Binds a non-singleton concrete class to an interface
     *
     * @param string|array $interfaces The interface or interfaces to bind to
     * @param string|null $concreteClass The concrete class to bind, or null if the interface actually is a concrete class
     * @param array $primitives The list of primitives to inject (must be in same order they appear in constructor)
     */
    public function bindPrototype($interfaces, string $concreteClass = null, array $primitives = []);

    /**
     * Binds a singleton concrete class to an interface
     *
     * @param string|array $interfaces The interface or interfaces to bind to
     * @param string|null $concreteClass The concrete class to bind, or null if the interface actually is a concrete class
     * @param array $primitives The list of primitives to inject (must be in same order they appear in constructor)
     */
    public function bindSingleton($interfaces, string $concreteClass = null, array $primitives = []);

    /**
     * Resolves a closure's parameters and calls it
     *
     * @param callable $closure The closure to resolve
     * @param array $primitives The list of primitives to inject (must be in same order they appear in closure)
     * @return mixed The result of the call
     * @throws IocException Thrown if there was an error calling the method
     */
    public function callClosure(callable $closure, array $primitives = []);

    /**
     * Resolves a method's parameters and calls it
     *
     * @param object $instance The instance whose method we're calling
     * @param string $methodName The name of the method we're calling
     * @param array $primitives The list of primitives to inject (must be in same order they appear in closure)
     * @param bool $ignoreMissingMethod Whether or not we ignore if the method does not exist
     * @return mixed The result of the call
     * @throws IocException Thrown if there was an error calling the method
     */
    public function callMethod(
        $instance,
        string $methodName,
        array $primitives = [],
        bool $ignoreMissingMethod = false
    );

    /**
     * Sets up the next binding call to use the input target class
     *
     * @param string $targetClass The target class
     * @return INewContainer The container with the target class set
     */
    public function for (string $targetClass) : INewContainer;

    /**
     * Gets whether or not an interface has a binding
     *
     * @param string $interface The interface to check
     * @return bool True if the interface has a binding, otherwise false
     */
    public function hasBinding(string $interface) : bool;

    /**
     * Resolve an instance of the interface
     *
     * @param string $interface The interface to resolve
     * @return mixed The resolved instance
     * @throws IocException Thrown if there was an error resolving the interface
     */
    public function resolve(string $interface);

    /**
     * Unbinds the interface from the container
     *
     * @param string|array $interface The interface or interfaces to unbind from
     */
    public function unbind($interface);
}