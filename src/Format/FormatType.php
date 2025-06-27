<?php

namespace TestPackage\Format;

enum FormatType: string
{
    case XML = 'xml';
    case JSON = 'json';
    case CSV = 'csv';

    public function getFormatClass(): FormatInterface
    {
        switch ($this) {
            case self::XML:
                return new FormatXML();
            case self::JSON:
                return new FormatJSON();
            case self::CSV:
                return new FormatCSV();
        }
    }
}
