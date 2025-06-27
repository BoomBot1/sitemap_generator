<?php

namespace TestPackage;

use DateTime;
use TestPackage\Exceptions\DataFieldsValidationException;
use TestPackage\Exceptions\DataValidationException;
use TestPackage\Exceptions\DirectoryException;
use TestPackage\Exceptions\FileException;
use TestPackage\Exceptions\FormatToFileException;
use TestPackage\Exceptions\FormatValidationException;
use TestPackage\Format\FormatInterface;
use TestPackage\Format\FormatType;
use TestPackage\Support\ChangeFreqType;
use Throwable;

final class SiteMapGenerator
{
    public array $pages;
    public FormatInterface $formatClass;
    private string $outputPath;
    private array $fields;

    /**
     * @throws FormatValidationException
     * @throws FormatToFileException
     */
    public function __construct(array $pages, string $formatType, string $outputPath)
    {
        $format = FormatType::tryFrom($formatType);

        if (!$format) {
            throw FormatValidationException::failure($formatType);
        } else if (pathinfo($outputPath, PATHINFO_EXTENSION) != $formatType) {
            throw FormatToFileException::failure();
        }

        $this->fields = [
            'loc',
            'lastmod',
            'priority',
            'changefreq',
        ];
        $this->formatClass = $format->getFormatClass();
        $this->pages = $pages;
        $this->outputPath = $outputPath;
    }

    /**
     * @throws DataFieldsValidationException
     * @throws DirectoryException
     * @throws FileException
     * @throws DataValidationException
     */
    public function generate(): void
    {
        $this->validate();
        $this->setDir($this->outputPath);
        $this->formatClass->generate($this->pages, $this->outputPath);
    }

    /**
     * @throws DataFieldsValidationException
     * @throws DataValidationException
     */
    public function validate(): void
    {
        foreach ($this->pages as $page) {
            if (!array_keys($page) == $this->fields) {
                throw DataFieldsValidationException::failure(array_keys($page), $this->fields);
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

    /**
     * @throws DirectoryException
     */
    public function setDir(string $filePath): void
    {
        $dir = dirname($filePath);

        if (!is_dir($dir)) {
            try {
                if (!mkdir($dir, 0755, true)) {
                    throw DirectoryException::failure("Can't reach output path '{$dir}'");
                }
            } catch (Throwable $e) {
                throw DirectoryException::failure($e->getMessage());
            }
        }
    }
}