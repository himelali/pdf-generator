<?php

namespace Himelali\PdfGenerator\Exceptions;

use Exception;
use Throwable;

class PdfGenerationException extends Exception
{
    protected $message = 'An error occurred during PDF generation.';

    public function __construct($message = null)
    {
        if ($message) {
            $this->message = $message;
        }

        parent::__construct($this->message);
    }

    public static function fromLibrary(string $library, Throwable $e)
    {
        return new self("PDF generation failed in {$library}: " . $e->getMessage(), $e->getCode(), $e);
    }

    public static function missingBinary(string $library)
    {
        return new self("The binary for {$library} is not set or could not be found.");
    }
}
