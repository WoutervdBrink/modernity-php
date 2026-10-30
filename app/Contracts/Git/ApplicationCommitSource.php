<?php

namespace App\Contracts\Git;

interface ApplicationCommitSource
{
    public function getApplicationCommit(): string;
}
