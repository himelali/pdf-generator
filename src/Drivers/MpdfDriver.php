<?php

namespace Himelali\PdfGenerator\Drivers;

use Exception;
use Himelali\PdfGenerator\Contracts\PdfDriverInterface;
use Himelali\PdfGenerator\Exceptions\PdfGenerationException;
use Himelali\PdfGenerator\Exceptions\RenderException;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class MpdfDriver implements PdfDriverInterface
{
    protected $mpdf = null;
    protected $html = '';
    protected $page_size = 'A4';
    protected $margins = ['top' => 10, 'right' => 10, 'bottom' => 10, 'left' => 10];
    protected $header_html = '';
    protected $footer_html = '';
    protected $footer_text = '';
    protected $page_numbers = false;
    protected $font_path = '';

    /**
     * @throws MpdfException
     * @throws PdfGenerationException
     */
    public function __construct()
    {
        $this->initializeMpdf();
    }

    /**
     * @throws PdfGenerationException
     */
    protected function initializeMpdf(): void
    {
        $config = [
            'mode' => 'utf-8',
            'format' => $this->page_size,
            'margin_top' => $this->margins['top'],
            'margin_right' => $this->margins['right'],
            'margin_bottom' => $this->margins['bottom'],
            'margin_left' => $this->margins['left'],
        ];

        if (!empty($this->font_path) && is_dir($this->font_path)) {
            $default_config = (new ConfigVariables())->getDefaults();
            $font_config = (new FontVariables())->getDefaults();

            $config['fontDir'] = array_merge($default_config['fontDir'], [$this->font_path]);
            $config['fontdata'] = $font_config['fontdata']; // Add your custom fonts here if needed
        }

        try {
            $this->mpdf = new Mpdf($config);
        } catch (Exception $e) {
            throw new PdfGenerationException('mPDF initialization failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws MpdfException|PdfGenerationException
     */
    public function setPageSize($size)
    {
        $this->page_size = $size;
        $this->initializeMpdf();

        return $this;
    }

    /**
     * @throws MpdfException
     * @throws PdfGenerationException
     */
    public function setMargins($top = 10, $right = 10, $bottom = 10, $left = 10)
    {
        $this->margins = compact('top', 'right', 'bottom', 'left');
        $this->initializeMpdf();

        return $this;
    }

    public function setHeader($header_html)
    {
        $this->header_html = $header_html;

        return $this;
    }

    public function setFooter($footer_html)
    {
        $this->footer_html = $footer_html;

        return $this;
    }

    public function setPageNumbers($enable)
    {
        $this->page_numbers = $enable;

        return $this;
    }

    public function setFooterText($footer_text)
    {
        $this->footer_text = $footer_text;

        return $this;
    }

    /**
     * @throws PdfGenerationException
     * @throws MpdfException
     */
    public function setFonts($font_path)
    {
        if (!is_dir($font_path)) {
            throw new PdfGenerationException("Font path does not exist: $font_path");
        }

        $this->font_path = $font_path;

        $this->initializeMpdf();

        return $this;
    }

    public function loadHtml($html, $encoding = null)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @throws PdfGenerationException
     */
    public function download($filename = 'document.pdf')
    {
        $this->prepareDocument();
        try {
            return $this->mpdf->Output($filename, 'D');
        } catch (Exception $e) {
            throw new RenderException('Error downloading mPDF file: ' . $e->getMessage());
        }
    }

    /**
     * @throws PdfGenerationException
     */
    public function stream($filename = 'document.pdf')
    {
        $this->prepareDocument();
        try {
            return $this->mpdf->Output($filename, 'I');
        } catch (Exception $e) {
            throw new RenderException('Error streaming mPDF file: ' . $e->getMessage());
        }
    }

    /**
     * @throws PdfGenerationException
     */
    protected function prepareDocument()
    {
        if ($this->header_html) {
            $this->mpdf->SetHTMLHeader($this->header_html);
        }

        if ($this->footer_html || $this->footer_text || $this->page_numbers) {
            $footer = $this->footer_html;
            if ($this->footer_text) {
                $footer .= '<div style="text-align: center;">' . $this->footer_text . '</div>';
            }
            if ($this->page_numbers) {
                $footer .= '<div style="text-align: right;">Page {PAGENO} of {nb}</div>';
            }
            $this->mpdf->SetHTMLFooter($footer);
        }
        try {
            $this->mpdf->WriteHTML($this->html);
        } catch (Exception $e) {
            throw new PdfGenerationException('Failed to load HTML into mPDF: ' . $e->getMessage());
        }
    }
}
