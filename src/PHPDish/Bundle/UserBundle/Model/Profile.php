<?php

namespace PHPDish\Bundle\UserBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use PHPDish\Bundle\CoreBundle\Model\IdentifiableTrait;
use PHPDish\Bundle\UserBundle\Model\ProfileInterface;
use PHPDish\Bundle\UserBundle\Model\UserInterface;

class Profile implements ProfileInterface
{
    use IdentifiableTrait;

    /**
     * 个人主页.
     *
     * @var string
     */
    protected $url;

    /**
     * 公司.
     *
     * @var string
     */
    protected $company;

    /**
     * 所在位置.
     *
     * @var string
     */
    protected $location;

    /**
     * 用户.
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Profile
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     *
     * @return Profile
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     *
     * @return Profile
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     *
     * @return Profile
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }
}
