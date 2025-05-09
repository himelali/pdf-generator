<?php

namespace Himelali\PdfGenerator\Exceptions;

use RuntimeException;

class RenderException extends RuntimeException
{
    protected $message = 'An error occurred while rendering the PDF.';

    public function __construct($message = null)
    {
        if ($message) {
            $this->message = $message;
        }

        parent::__construct($this->message);
    }
}
