<?php

namespace ContainerHtHw79I;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_QAdwjytService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private '.service_locator.qAdwjyt' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.qAdwjyt'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'prize' => ['privates', '.errored..service_locator.qAdwjyt.App\\Entity\\Prize', NULL, 'Cannot autowire service ".service_locator.qAdwjyt": it references class "App\\Entity\\Prize" but no such service exists.'],
        ], [
            'prize' => 'App\\Entity\\Prize',
        ]);
    }
}
