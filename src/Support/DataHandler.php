<?php

namespace TestPackage\Support;

use DateTime;
use TestPackage\Exceptions\DataFieldsValidationException;
use TestPackage\Exceptions\DataValidationException;


/**
 * Validates and format data.
 */
final class DataHandler
{
    /**
     * @throws DataValidationException
     * @throws DataFieldsValidationException
     */
    public static function validateData(array $data, array $fields): void
    {
        foreach ($data as $page) {
            if (!array_keys($page) == $fields) {
                throw DataFieldsValidationException::failure(array_keys($page), $fields);
            }

            if (
                !filter_var($page['loc'], FILTER_VALIDATE_URL)
                || !$page['lastmod'] instanceof DateTime
                || !filter_var($page['priority'], FILTER_VALIDATE_FLOAT)
                || !ChangeFreqType::tryFrom($page['changefreq'])
            ) {
                throw DataValidationException::failure($page);
            }
        }
    }

    public static function formatData(array $data): array
    {
        $pages = [];

        foreach ($data as $page) {
            $page['lastmod'] = $page['lastmod']->format('Y-m-d');
            $pages[] = $page;
        }

        return $pages;
    }
}
