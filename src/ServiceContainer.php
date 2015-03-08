<?php

/**
 * @file
 * Creates or returns a container to manage global services.
 */

namespace Itafroma\Zork;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ServiceContainer
{
    const DEFAULT_CONFIG = __DIR__ . '/../config/';

    /**
     * Retrieves a the service container, ensuring only one instance exists.
     *
     * @param boolean $reset Creates a new service container, destroying the old one.
     * @param string $config_file The path to the YAML configuration file defining the service container.
     * @return Symfony\Component\DependencyInjection\ContainerBuilder The service container.
     */
    public static function getContainer($reset = false, $config_file = self::DEFAULT_CONFIG)
    {
        static $container = null;

        if ($container === null || $reset) {
            $container = static::create($config_file);
        }

        return $container;
    }

    /**
     * Creates a new service container.
     *
     * @param string $config_file The path to the YAML configuration file defining the service container.
     * @return Symfony\Component\DependencyInjection\ContainerBuilder The service container.
     */
    public static function create($config_file = self::DEFAULT_CONFIG)
    {
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader($container, new FileLocator($config_file));
        $loader->load('services.yml');

        return $container;
    }
}
