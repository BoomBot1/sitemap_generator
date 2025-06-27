<?php

namespace TestPackage\Exceptions;

use TestPackage\Format\FormatType;

final class FormatValidationException extends BaseException
{
    public static function failure(string $received): self
    {
        $expectsString = implode(', ', array_column(FormatType::cases(), 'value'));

        return new self("Invalid format requested. Expected: [{$expectsString}]; Received: [{$received}]");
    }
}