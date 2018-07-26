<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\Collection;
use PHPDish\Bundle\ResourceBundle\Model\DateTimeInterface;
use PHPDish\Bundle\ResourceBundle\Model\EnabledInterface;
use PHPDish\Bundle\UserBundle\Model\UserAwareInterface;
use PHPDish\Bundle\UserBundle\Model\UserInterface;

interface PostInterface extends
    ContentInterface, DateTimeInterface, UserAwareInterface, VotableInterface, EnabledInterface
{
    /**
     * 获取标题.
     *
     * @return string
     */
    public function getTitle();

    /**
     * 设置标题.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);

    /**
     * 获取查看次数.
     *
     * @return int
     */
    public function getViewCount();

    /**
     * 设置查看次数.
     *
     * @param int $viewCount
     *
     * @return $this
     */
    public function setViewCount($viewCount);

    /**
     * 获取评论数量.
     *
     * @return int
     */
    public function getCommentCount();

    /**
     * 添加阅读数
     *
     * @param int $viewCount
     * @return $this
     */
    public function addViewCount($viewCount);

    /**
     * 设置评论数量.
     *
     * @param int $commentCount
     *
     * @return $this
     */
    public function setCommentCount($commentCount);

    /**
     * 自增评论数量.
     *
     * @param int $count
     *
     * @return $this
     */
    public function addCommentCount($count = 1);

    /**
     * 获取字数.
     *
     * @return int
     */
    public function getWordCount();

    /**
     * 获取摘要
     *
     * @return string
     */
    public function getSummary();

    /**
     * 提取文章中的图片.
     *
     * @return array
     */
    public function getImages();

    /**
     * 检查文章是否是属于指定用户.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isBelongsTo(UserInterface $user);

    /**
     * 获取上次回复时间
     *
     * @return \DateTimeInterface
     */
    public function getLastCommentAt();

    /**
     * 设置回复时间
     *
     * @param \DateTimeInterface $lastCommentAt
     * @return self
     */
    public function setLastCommentAt(\DateTimeInterface $lastCommentAt);

    /**
     * 获取上次评论的用户
     *
     * @return UserInterface
     */
    public function getLastCommentUser();

    /**
     * 设置回复人
     *
     * @param UserInterface $lastCommentUser
     * @return self
     */
    public function setLastCommentUser(UserInterface $lastCommentUser);

    /**
     * 获取评论
     *
     * @return CommentInterface[]|Collection
     */
    public function getComments();
}
