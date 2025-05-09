<?php

namespace Himelali\PdfGenerator\Facades;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface setPageSize($paper)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface setMargins($top, $right, $bottom, $left)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface setHeader($header_html)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface setFooter($footer_html)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface setFooterText($footer_text)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface setPageNumbers($enable)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface setFonts($font_path)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface loadHTML($string, $encoding = null)
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface download($filename = 'document.pdf')
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface stream($filename = 'document.pdf')
 * @method static \Himelali\PdfGenerator\Contracts\PdfDriverInterface driver($driver = null)
 */
class Pdf extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pdf.generator';
    }
}
