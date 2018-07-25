<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\ResourceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

abstract class AbstractConfiguration
{
    protected function addTemplatesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('default_templates_namespace')->defaultNull()->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->scalarPrototype()->end()
                ->end()
            ->end();
    }
}