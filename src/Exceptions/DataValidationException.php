<?php

namespace TestPackage\Exceptions;

final class DataValidationException extends BaseException
{
    public static function failure($page): self
    {
        return new self("Invalid data received. loc: {$page['loc']}, lastmod: {$page['lastmod']->getTimestamp()}, changefreq: {$page['changefreq']}, priority: {$page['priority']}");
    }
}