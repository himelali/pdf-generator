<?php

namespace Himelali\PdfGenerator\Exceptions;

use Exception;

class BinaryNotFoundException extends Exception
{
    protected $message = 'The binary executable for the PDF driver was not found. Please check the configuration.';

    public function __construct($driver)
    {
        $this->message = "The binary executable for the {$driver} driver was not found. Please check the configuration.";

        parent::__construct($this->message);
    }
}
