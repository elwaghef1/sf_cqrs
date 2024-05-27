<?php

namespace App\Application\Command;

class DeletePostCommand
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
