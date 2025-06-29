<?php

namespace TestPackage\Format;

use TestPackage\Exceptions\FileException;
use Throwable;

final class GeneratorCSV implements GeneratorInterface
{

    /**
     * @throws FileException
     */
    public function generate(array $data, string $outputPath): void
    {
        try {
            $file = fopen($outputPath, 'w');
            fputcsv($file, array_keys($data[0]), ';');

            foreach ($data as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        } catch (Throwable $e) {
            throw FileException::failure($e->getMessage());
        }

    }
}