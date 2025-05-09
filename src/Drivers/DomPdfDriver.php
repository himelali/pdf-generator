<?php

namespace Himelali\PdfGenerator\Drivers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Himelali\PdfGenerator\Contracts\PdfDriverInterface;
use Himelali\PdfGenerator\Exceptions\PdfGenerationException;
use Himelali\PdfGenerator\Exceptions\RenderException;
use Throwable;

class DomPdfDriver implements PdfDriverInterface
{
    protected $dompdf;
    protected $options;
    protected $html = null;
    protected $encoding = 'UTF-8';
    protected $header = null;
    protected $footer = null;
    protected $footer_text = null;
    protected $page_size = 'A4';
    protected $margins = ['top' => 10, 'right' => 10, 'bottom' => 10, 'left' => 10];
    protected $config = [];

    /**
     * @throws PdfGenerationException
     */
    public function __construct($config = [])
    {
        $this->config = $config;
        try {
            $this->options = new Options();
            $this->options->set('isHtml5ParserEnabled', $this->config['options']['is_html5_parser_enabled'] ?? true);
            $this->options->set('isRemoteEnabled', $this->config['options']['is_remote_enabled'] ?? true);
            $this->options->set('defaultFont', $config['default_font'] ?? 'DejaVu Sans');
            $this->options->set('isPhpEnabled', $this->config['options']['is_php_enabled'] ?? true);
            $this->options->set('isFontSubsettingEnabled', $this->config['options']['is_font_sub_setting_enabled'] ?? true);
            $this->dompdf = new Dompdf($this->options);
        } catch (Exception $e) {
            throw new PdfGenerationException('DomPDF initialization failed: ' . $e->getMessage());
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
        $this->header = $header_html;

        return $this;
    }

    public function setFooter($footer_html)
    {
        $this->footer = $footer_html;

        return $this;
    }

    public function setPageNumbers($enable)
    {
        return $this;
    }

    public function setFooterText($footer_text)
    {
        return $this;
    }

    public function loadHtml($html, $encoding = 'UTF-8')
    {
        $this->html = $html;
        $this->encoding = $encoding;

        return $this;
    }

    /**
     */
    public function download($filename = 'document.pdf')
    {
        try {
            $content = $this->applyTemplate();
            $this->dompdf->loadHtml($content, $this->encoding);
            if(! empty($this->page_size)) {
                $this->dompdf->setPaper($this->page_size);
            }
            $this->dompdf->render();

            $this->dompdf->stream($filename, ['Attachment' => 1]);
        } catch (Exception $e) {
            throw new RenderException('Error downloading DomPDF file: ' . $e->getMessage());
        }
    }

    /**
     */
    public function stream($filename = 'document.pdf')
    {
        try {
            $content = $this->applyTemplate();
            $this->dompdf->loadHtml($content);
            if (! empty($this->page_size)) {
                $this->dompdf->setPaper($this->page_size);
            }
            $this->dompdf->render();
            $this->dompdf->stream($filename, ['Attachment' => 0]);
        } catch (Exception $e) {
            throw new RenderException('Error streaming DomPDF file: ' . $e->getMessage());
        }
    }

    /**
     * @throws PdfGenerationException
     */
    public function setFonts($font_path)
    {
        if (! empty($font_path)) {
            if (! is_dir($font_path)) {
                throw new PdfGenerationException("Font directory '{$font_path}' does not exist.");
            }

            $this->options->setChroot($font_path);
        }
    }

    protected function applyTemplate()
    {
        $style = "
            <style>
                @page {
                    margin-top: {$this->margins['top']}mm;
                    margin-right: {$this->margins['right']}mm;
                    margin-bottom: {$this->margins['bottom']}mm;
                    margin-left: {$this->margins['left']}mm;
                }
                body { font-family: 'DejaVu Sans', sans-serif; }
                header { position: fixed; top: -60px; left: 0; right: 0; height: 50px; }
                footer { position: fixed; bottom: -60px; left: 0; right: 0; height: 50px; font-size: 10px; text-align: center; }
            </style>
        ";

        $header = $this->header ? "<header>{$this->header}</header>" : '';
        $footer = $this->footer ? "<footer>{$this->footer}</footer>" : '';

        if (!$footer && $this->footer_text) {
            $footer = "<footer>{$this->footer_text}</footer>";
        }

        return $style . $header . $footer . $this->html;
    }
}
