<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\ThemeBundle;

use PHPDish\Bundle\ThemeBundle\DependencyInjection\PHPDishThemeExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PHPDishThemeBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new PHPDishThemeExtension();
        }
        return $this->extension;
    }
}