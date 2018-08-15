<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\ThemeBundle\Templating\Loader;

use PHPDish\Bundle\ThemeBundle\Theming\ThemeManagerInterface;
use Symfony\Component\HttpKernel\Config\FileLocator as BaseFileLocator;

class FileLocator extends BaseFileLocator
{
    /**
     * @var ThemeManagerInterface
     */
    protected $themeManager;

    public function setThemeManager(ThemeManagerInterface $themeManager)
    {
        $this->themeManager = $themeManager;
        //将当前主题的路径添加入paths
        if ($currentTheme = $this->themeManager->getCurrentTheme()) {
            $this->paths[] = $currentTheme->getPath();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function locate($file, $currentPath = null, $first = true)
    {
        return parent::locate($file, $currentPath);
    }
}