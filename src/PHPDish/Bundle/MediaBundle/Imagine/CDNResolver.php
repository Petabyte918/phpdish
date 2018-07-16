<?php

namespace PHPDish\Bundle\MediaBundle\Imagine;

use Liip\ImagineBundle\Imagine\Cache\Resolver\WebPathResolver;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\RequestContext;

class CDNResolver extends WebPathResolver
{
    /**
     * @var string
     */
    protected $baseUrl;

    public function __construct(
        Filesystem $filesystem,
        RequestContext $requestContext,
        string $webRootDir,
        string $cachePrefix = 'media/cache',
        string $baseUrl
    ) {
        parent::__construct($filesystem, $requestContext, $webRootDir, $cachePrefix);
        $this->baseUrl = $baseUrl;
    }


    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return $this->baseUrl ?: parent::getBaseUrl();
    }
}