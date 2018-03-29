<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace PHPDish\QiNiuPlugin;

use PHPDish\Bundle\CoreBundle\Plugin\SimplePlugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class QiNiuPlugin extends SimplePlugin
{
    public function registerServices(ContainerBuilder $container)
    {
    }
}