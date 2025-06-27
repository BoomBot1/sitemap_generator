<?php

namespace TestPackage\Exceptions;

use Throwable;

abstract class BaseException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $fullMessage = $this->formatMessage($message);

        parent::__construct($message, $code, $previous);
    }

    protected function formatMessage(string $message): string
    {
        return "[".static::class."] {$message}";
    }
}