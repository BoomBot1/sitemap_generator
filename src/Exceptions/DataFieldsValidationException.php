<?php

namespace TestPackage\Exceptions;

final class DataFieldsValidationException extends BaseException
{
    public static function failure(array $data, array $expects): self
    {
        $expectsString = implode(', ', $expects);
        $dataString = implode(', ', array_keys($data));

        return new self("Data is invalid. Expected fields: [{$expectsString}]; Received: [{$dataString}]");
    }
}