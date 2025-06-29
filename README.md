Using:
* Init SiteMapGenerator class via passing argumets (documented). Use generate() function from object (SiteMapGenerator).

# If u need to add extensions or manipulate data validation\data formatting:
## src/Support/ DataHandler.php:
* Validation and formatting data.
## src/Support ChangeFreqType.php:
* Strings for "changefreq" attribute.
## src/Format/ GeneratorInterface.php:
* Interface u need to implement from in your GeneratorClass.
## src/Format/ FormatType.php:
* Add your extension and GeneratorClass after creating the new Gen class.

# Exceptions:
* DataFieldsValidationException: Throws when fields (keys) in data array didn't match predefined fields.
* DataValidationException: Throws when some data didn't match predefined rules.
* DirectoryException: Throws when package can't create directory.
* FileException: Throws when package can't create\open file in output path.
* FormatToFileException: Throws when $formatType didn't match file's extension in $outputPath
* FormatValidationException: Throws when passed $formatType didn't match any predefined formats (CSV, JSON, XML)

# Requires:
* PHP: >= 8.3
* XmlWriter
  
