<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Bundle\CoreBundle\EventListener;

use PHPDish\Bundle\ForumBundle\Event\ReplyMentionUserEvent;
use PHPDish\Bundle\PostBundle\Event\CommentMentionUserEvent;

final class MentionUserListener extends EventListener
{
    /**
     * 当用户在文章评论中被提及.
     *
     * @param CommentMentionUserEvent $event
     */
    public function onUserMentionedInComment(CommentMentionUserEvent $event)
    {
        foreach ($event->getMentionedUsers() as $user) {
            if (
                $event->getComment()->getUser() === $user
                || $event->getComment()->getPost()->getUser() === $user
            ) {
                continue;
            }
            $this->notificationManager->createMentionUserInPostNotification($user, $event->getComment());
        }
    }

    /**
     * 当用户在话题回复中被提及.
     *
     * @param ReplyMentionUserEvent $event
     */
    public function onUserMentionedInReply(ReplyMentionUserEvent $event)
    {
        foreach ($event->getMentionedUsers() as $user) {
            if (
                $event->getReply()->getUser() === $user //不能艾特自己
                || $event->getReply()->getTopic()->getUser() === $user //不能艾特楼主，楼主本身就会收到消息
            ) {
                continue;
            }
            $this->notificationManager->createAtUserInTopicNotification($user, $event->getReply());
        }
    }
}
