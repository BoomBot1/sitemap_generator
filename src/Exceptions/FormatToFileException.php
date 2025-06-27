<?php

namespace TestPackage\Exceptions;

final class FormatToFileException extends BaseException
{
    public static function failure(): self
    {
        return new self("The file format does not match the requested format");
    }
}