<?php

namespace App\Services\Contracts;

interface ProfileProviderInterface
{
    public function fetch(string $username): array;
}