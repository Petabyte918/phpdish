<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace PHPDish\Bundle\UserBundle;

use PHPDish\Bundle\ResourceBundle\AbstractBundle;
use PHPDish\Bundle\UserBundle\DependencyInjection\Compiler\FOSCompatiblePass;
use PHPDish\Bundle\UserBundle\DependencyInjection\PHPDishUserExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PHPDishUserBundle extends AbstractBundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FOSCompatiblePass());
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new PHPDishUserExtension();
        }
        return $this->extension;
    }
}
