<?php declare(strict_types=1);

namespace App\Infrastructure\Exception;

interface TranslatableException
{
    public function getMessageData(): array;
}
