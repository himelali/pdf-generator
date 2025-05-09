<?php

namespace Himelali\PdfGenerator\Drivers;

use Exception;
use Himelali\PdfGenerator\Contracts\PdfDriverInterface;
use Himelali\PdfGenerator\Exceptions\BinaryNotFoundException;
use Himelali\PdfGenerator\Exceptions\PdfGenerationException;
use Himelali\PdfGenerator\Exceptions\RenderException;
use Knp\Snappy\Pdf;

class SnappyDriver implements PdfDriverInterface
{
    protected $page_size = 'A4';
    protected $margins = ['top' => 10, 'right' => 10, 'bottom' => 10, 'left' => 10];
    protected $header_html = '';
    protected $footer_html = '';
    protected $footer_text = '';
    protected $page_numbers = false;
    protected $html = '';
    protected $snappy;

    /**
     * @throws BinaryNotFoundException
     * @throws PdfGenerationException
     */
    public function __construct()
    {
        $binaryPath = config('snappy.pdf.binary');
        if (!file_exists($binaryPath) || !is_executable($binaryPath)) {
            throw new BinaryNotFoundException('Snappy');
        }

        try {
            $this->snappy = new Pdf($binaryPath);
        } catch (Exception $e) {
            throw new PdfGenerationException('Failed to initialize Snappy PDF driver: ' . $e->getMessage());
        }
    }

    public function setPageSize($size)
    {
        $this->page_size = $size;
        return $this;
    }

    public function setMargins($top = 10, $right = 10, $bottom = 10, $left = 10)
    {
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
        $this->page_numbers = $enable;
        return $this;
    }

    public function setFooterText($footer_text)
    {
        $this->footer_text = $footer_text;
        return $this;
    }

    public function setFonts($font_path)
    {
        // Fonts should be embedded via CSS in the HTML for Snappy
        return $this;
    }

    public function loadHtml($html, $encoding = null)
    {
        $this->html = $html;

        return $this;
    }

    protected function applySettings()
    {
        $this->snappy->setOption('page-size', $this->page_size);

        $this->snappy->setOption('margin-top', $this->margins['top']);
        $this->snappy->setOption('margin-right', $this->margins['right']);
        $this->snappy->setOption('margin-bottom', $this->margins['bottom']);
        $this->snappy->setOption('margin-left', $this->margins['left']);

        if ($this->header_html) {
            $this->snappy->setOption('header-html', $this->generateTempFile($this->header_html, 'header'));
        }

        if ($this->footer_html || $this->footer_text || $this->page_numbers) {
            $footer_content = $this->footer_html;

            if ($this->footer_text) {
                $footer_content .= "<div style='text-align: center;'>{$this->footer_text}</div>";
            }

            if ($this->page_numbers) {
                $footer_content .= "<div style='text-align: right;'>Page [page] of [topage]</div>";
            }

            $this->snappy->setOption('footer-html', $this->generateTempFile($footer_content, 'footer'));
        }
    }

    protected function generateTempFile($content, $prefix)
    {
        $path = storage_path("app/pdf/tmp/{$prefix}_" . uniqid() . '.html');
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0775, true);
        }
        file_put_contents($path, $content);

        return $path;
    }

    public function download($filename = 'document.pdf')
    {
        try {
            $this->applySettings();
            return response()->stream(function () use ($filename) {
                echo $this->snappy->getOutput();
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (Exception $e) {
            throw new RenderException('Error generating PDF: ' . $e->getMessage());
        }
    }

    public function stream($filename = 'document.pdf')
    {
        try {
            $this->applySettings();
            return response()->stream(function () use ($filename) {
                echo $this->snappy->getOutput();
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (Exception $e) {
            throw new RenderException('Error rendering PDF: ' . $e->getMessage());
        }
    }
}
