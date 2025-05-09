<?php

namespace Himelali\PdfGenerator\Exceptions;

use Exception;

class InvalidDriverException extends Exception
{
    protected $message = 'Invalid PDF driver specified.';

    public function __construct($driver)
    {
        $this->message = "The specified PDF driver '{$driver}' is invalid. Please check the configuration.";

        parent::__construct($this->message);
    }
}
