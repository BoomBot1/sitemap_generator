<?php

namespace TestPackage\Format;

use DateTime;
use TestPackage\Exceptions\FileException;
use Throwable;

final class FormatJSON implements FormatInterface
{
    public function generate(array $data, string $outputPath): void
    {
        try {
            $file = fopen($outputPath, 'w');
            $json = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($outputPath, $json);
            fclose($file);
        } catch (Throwable $e) {
            throw FileException::failure($e->getMessage());
        }
    }

    private function formatDateTime(DateTime $date): string
    {
        return (string)$date->format('Y-m-d');
    }
}