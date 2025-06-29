<?php

namespace TestPackage;

use DateTime;
use TestPackage\Exceptions\DataFieldsValidationException;
use TestPackage\Exceptions\DataValidationException;
use TestPackage\Exceptions\DirectoryException;
use TestPackage\Exceptions\FileException;
use TestPackage\Exceptions\FormatToFileException;
use TestPackage\Exceptions\FormatValidationException;
use TestPackage\Format\GeneratorInterface;
use TestPackage\Format\FormatType;
use TestPackage\Support\ChangeFreqType;
use TestPackage\Support\DataHandler;
use Throwable;

final class SiteMapGenerator
{
    public array $pages;
    public GeneratorInterface $formatClass;
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
        DataHandler::validateData($this->pages, $this->fields);
        $this->pages = DataHandler::formatData($this->pages);
        $this->setDir($this->outputPath);
        $this->formatClass->generate($this->pages, $this->outputPath);
    }

    /**
     * @throws DirectoryException
     */
    private function setDir(string $filePath): void
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

    private function formatData(): void
    {
        $pages = [];

        foreach ($this->pages as $page) {
            $page['lastmod'] = $page['lastmod']->format('Y-m-d');
            $pages[] = $page;
        }
        $this->pages = $pages;
    }
}