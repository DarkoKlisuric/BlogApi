<?php

namespace App\Handler;

/**
 * Interface PublishedDateEntityInterface
 * @package App\Handler
 */
interface  PublishedDateEntityInterface
{
    /**
     * @param \DateTimeInterface $published
     * @return PublishedDateEntityInterface
     */
    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface;
}