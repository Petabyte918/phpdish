<?php

/*
 * This file is part of the phpdish/phpdish
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PHPDish\Component\Mention;

interface MentionParserInterface
{
    /**
     * Parse the text.
     *
     * @param $body
     *
     * @return $this
     */
    public function parse($body);

    /**
     * Gets the parsed body.
     *
     * @return string
     */
    public function getParsedBody();

    /**
     * Gets the mentioned user names.
     *
     * @return array
     */
    public function getMentionedNames();

    /**
     * Gets the mentioned users.
     *
     * @return array
     */
    public function getMentionedUsers();
}
