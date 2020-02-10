<?php

namespace App\Handler;

interface  PublishedDateEntityInterface
{
    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface;
}