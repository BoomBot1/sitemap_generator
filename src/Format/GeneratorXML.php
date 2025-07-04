<?php

namespace TestPackage\Format;

use TestPackage\Exceptions\FileException;
use Throwable;
use XMLWriter;

final class GeneratorXML implements GeneratorInterface
{
    private const string SCHEMA_LOCATION = 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';
    private const string XML_NAMESPACE = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    private const string XSI_NAMESPACE = 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';

    public function generate(array $data, string $outputPath): void
    {
        try {
            $xml = new XMLWriter();
            $xml->openMemory();
            $xml->setIndent(true);
            $xml->startDocument('1.0', 'UTF-8');

            $xml->startElement('urlset');
            $xml->writeAttribute('xmlns:xsi', self::XSI_NAMESPACE);
            $xml->writeAttribute('xmlns', self::XML_NAMESPACE);
            $xml->writeAttribute('xsi:schemaLocation', self::SCHEMA_LOCATION);

            foreach ($data as $page) {
                $this->addUrlElement($xml, $page);
            }

            $xml->endElement();
            $xml->endDocument();

            $this->saveToFile($xml->outputMemory(), $outputPath);
        } catch (Throwable $e) {
            throw FileException::failure($e->getMessage());
        }
    }

    private function addUrlElement(XMLWriter $xml, array $page): void
    {
        $xml->startElement('url');

        $xml->writeElement('loc', $page['loc']);
        $xml->writeElement('lastmod', $page['lastmod']);
        $xml->writeElement('priority', $page['priority']);
        $xml->writeElement('changefreq', $page['changefreq']);

        $xml->endElement();
    }

    /**
     * @throws FileException
     */
    private function saveToFile(string $content, string $path): void
    {
        $dir = dirname($path);
        
        if (is_file($path) && !is_writable($path)) {
            throw FileException::failure("File is not writable: {$path}");
        }

        $tmpFile = @tempnam($dir, 'sitemap_');

        if ($tmpFile === false) {
            throw FileException::failure("Failed to create temporary file in {$dir}");
        }

        try {
            if (@file_put_contents($tmpFile, $content) === false) {
                throw FileException::failure("Failed to write XML content to temporary file");
            }

            if (!@rename($tmpFile, $path)) {
                throw FileException::failure("Failed to move file to {$path}");
            }
        } finally {
            if (file_exists($tmpFile)) {
                @unlink($tmpFile);
            }
        }
    }
}