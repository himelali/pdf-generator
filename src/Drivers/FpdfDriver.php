<?php

namespace Himelali\PdfGenerator\Drivers;

use Exception;
use FPDF;
use Himelali\PdfGenerator\Contracts\PdfDriverInterface;
use Himelali\PdfGenerator\Exceptions\PdfGenerationException;
use Himelali\PdfGenerator\Exceptions\RenderException;

class FpdfDriver extends FPDF implements PdfDriverInterface
{
    protected $html = '';
    protected $font_dir = '';
    protected $footer_text = '';
    protected $header_html = '';
    protected $footer_html = '';
    protected $show_page_numbers = false;
    protected $margins = ['top' => 10, 'right' => 10, 'bottom' => 10, 'left' => 10];

    /**
     * @throws PdfGenerationException
     */
    public function __construct()
    {
        parent::__construct();
        try {
            $this->AddPage();
        } catch (Exception $e) {
            throw new PdfGenerationException('FPDF initialization failed: ' . $e->getMessage());
        }
    }

    public function setPageSize($size)
    {
        $this->AddPage($size);

        return $this;
    }

    public function setMargins($top = 10, $right = 10, $bottom = 10, $left = 10)
    {
        parent::SetMargins($left, $top, $right);
        $this->SetAutoPageBreak(true, $bottom);
        $this->margins = compact('top', 'right', 'bottom', 'left');

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
        $this->show_page_numbers = $enable;

        return $this;
    }

    public function setFooterText($footer_text)
    {
        $this->footer_text = $footer_text;

        return $this;
    }

    /**
     * @throws PdfGenerationException
     */
    public function setFonts($font_path)
    {
        if (!is_dir($font_path)) {
            throw new PdfGenerationException("FPDF font directory does not exist: {$font_path}");
        }

        $this->font_dir = $font_path;
        define('FPDF_FONTPATH', $this->font_dir . DIRECTORY_SEPARATOR);

        return $this;
    }

    public function loadHtml($html, $encoding = null)
    {
        // For real-world use, you may replace this with a full HTML parser like `html2pdf`.
        $this->html = strip_tags($html); // FPDF cannot render full HTML; requires parsing

        return $this;
    }

    public function Header()
    {
        if ($this->header_html) {
            $this->SetY($this->margins['top']);
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(0, 5, $this->header_html);
        }
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);

        if ($this->footer_text) {
            $this->Cell(0, 10, $this->footer_text, 0, 0, 'C');
        }

        if ($this->show_page_numbers) {
            $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'R');
        }

        if ($this->footer_html) {
            $this->SetY(-30);
            $this->MultiCell(0, 5, $this->footer_html);
        }
    }

    public function download($filename = 'document.pdf')
    {
        $this->generate();
        try {
            return $this->Output('D', $filename);
        } catch (Exception $e) {
            throw new RenderException('Error downloading FPDF file: ' . $e->getMessage());
        }
    }

    public function stream($filename = 'document.pdf')
    {
        $this->generate();

        try {
            return $this->Output('I', $filename);
        } catch (Exception $e) {
            throw new RenderException('Error streaming FPDF file: ' . $e->getMessage());
        }
    }

    protected function generate()
    {
        try {
            $this->SetFont('Arial', '', 12);
            $this->MultiCell(0, 10, $this->html);
        } catch (Exception $e) {
            throw new RenderException('Error downloading FPDF file: ' . $e->getMessage());
        }
    }
}
