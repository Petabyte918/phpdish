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

namespace PHPDish\Component\Media\Manager;

use Gaufrette\Filesystem;
use PHPDish\Component\Media\Model\FileInterface;

class FileManager implements FileManagerInterface
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(FileInterface $file, $overwrite = true)
    {
        $this->filesystem->write($file->getKey(), $file->getContent(), $overwrite);
    }

    /**
     * {@inheritdoc}
     */
    public function download(FileInterface $file)
    {
        $content = $this->filesystem->read($file->getKey());
        $file->setContent($content);
    }

    /**
     * {@inheritdoc}
     */
    public function has($file)
    {
        $key = $file instanceof FileInterface ? $file->getKey() : $file;

        return $this->filesystem->has($key);
    }
}
