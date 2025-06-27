<?php

namespace TestPackage\Exceptions;

final class DirectoryException extends BaseException
{
    public static function failure(string $message): self
    {
        return new self("Failed to create a directory: {$message}");
    }
}