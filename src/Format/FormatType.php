<?php

namespace TestPackage\Format;

enum FormatType: string
{
    case XML = 'xml';
    case JSON = 'json';
    case CSV = 'csv';

    public function getGeneratorClass(): GeneratorInterface
    {
        switch ($this) {
            case self::XML:
                return new GeneratorXML();
            case self::JSON:
                return new GeneratorJSON();
            case self::CSV:
                return new GeneratorCSV();
        }
    }
}
