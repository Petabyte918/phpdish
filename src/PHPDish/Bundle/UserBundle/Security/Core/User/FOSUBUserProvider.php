<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\UserBundle\Security\Core\User;

use Carbon\Carbon;
use FOS\UserBundle\Model\UserManagerInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseFOSUBProvider;
use PHPDish\Component\Media\Manager\FileDownloaderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseFOSUBProvider
{
    /**
     * @var FileDownloaderInterface
     */
    protected $fileDownloader;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(
        UserManagerInterface $userManager,
        TokenStorageInterface $tokenStorage,
        FileDownloaderInterface $fileDownloader,
        array $properties
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->fileDownloader = $fileDownloader;
        parent::__construct($userManager, $properties);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        //优先查找对应用户，否则使用当前登录用户，否则按照邮箱查找，否则创建新用户
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy([$this->getProperty($response) => $username]) ?: $this->getAuthenticatedUser();
        if (!$user) {
            $user = $this->userManager->findUserByEmail($response->getEmail()) ?: $this->createNewUser($response);
        }

        $serviceName = $response->getResourceOwner()->getName();
        $accessTokenSetter = 'set'.ucfirst($serviceName).'AccessToken';
        $user->$accessTokenSetter($response->getAccessToken());
        $idSetter = 'set'.ucfirst($serviceName).'Id';
        $user->$idSetter($response->getUsername());

        return $user;
    }

    /**
     * 创建一个新用户.
     *
     * @param UserResponseInterface $response
     *
     * @return \PHPDish\Bundle\UserBundle\Model\UserInterface
     */
    protected function createNewUser(UserResponseInterface $response)
    {
        $user = $this->userManager->createUser()
            ->setEnabled(true)
            ->setUsername($this->generateUsername($response))
            ->setPassword('')
            ->setEmail($response->getEmail() ?: '')
            ->setCreatedAt($now = Carbon::now())
            ->setUpdatedAt($now);

        try {
            $avatar = $this->fileDownloader->download($response->getProfilePicture());
            $user->setAvatar($avatar->getKey());
        } catch (\Exception $exception) {
            $user->setAvatar('');
        }

        return $user;
    }

    /**
     * 生成用户名.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     */
    protected function generateUsername(UserResponseInterface $response)
    {
        $username = $response->getNickname();

        return $this->userManager->findUserByUsername($username)
            ? $username.$response->getUsername()
            : $username;
    }

    /**
     * 获取当前登录的用户
     * @return UserInterface
     */
    protected function getAuthenticatedUser()
    {
        return ($token = $this->tokenStorage->getToken())
            ? ($token->getUser() instanceof UserInterface ? $token->getUser() : null)
            : null;
    }
}
