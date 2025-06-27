<?php

namespace TestPackage\Exceptions;

final class FileException extends BaseException
{
    public static function failure(string $message): self
    {
        return new self("Failed to create a file: {$message}");
    }
}