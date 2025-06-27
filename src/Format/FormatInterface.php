<?php

namespace TestPackage\Format;

use TestPackage\Exceptions\FileException;

interface FormatInterface
{
    /**
     * @throws FileException
     */
    public function generate(array $data, string $outputPath): void;
}