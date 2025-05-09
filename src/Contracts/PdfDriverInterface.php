<?php

namespace Himelali\PdfGenerator\Contracts;

/**
 * Interface PdfDriverInterface
 *
 * Defines the contract that all PDF drivers must implement.
 */
interface PdfDriverInterface
{
    /**
     * Set the page size of the PDF (e.g., A4, Letter).
     *
     * @param string $size
     * @return self
     */
    public function setPageSize($size);

    /**
     * Set the margins of the PDF (in mm or unit supported by a driver).
     *
     * @param float|int $top
     * @param float|int $right
     * @param float|int $bottom
     * @param float|int $left
     * @return self
     */
    public function setMargins($top = 10, $right = 10, $bottom = 10, $left = 10);

    /**
     * Set the HTML content to be used as a header.
     *
     * @param string $header_html
     * @return self
     */
    public function setHeader($header_html);

    /**
     * Set the HTML content to be used as footer.
     *
     * @param string $footer_html
     * @return self
     */
    public function setFooter($footer_html);

    /**
     * Enable or disable automatic page numbers.
     *
     * @param bool $enable
     * @return self
     */
    public function setPageNumbers($enable);

    /**
     * Set plain text content for the footer (optional in some drivers).
     *
     * @param string $footer_text
     * @return self
     */
    public function setFooterText($footer_text);

    /**
     * Set custom font path or configuration.
     *
     * @param string $font_path
     * @return self
     */
    public function setFonts($font_path);

    /**
     * Load raw HTML content into the PDF renderer.
     *
     * @param string $html
     * @param string|null $encoding
     * @return self
     */
    public function loadHtml($html, $encoding = 'UTF-8');

    /**
     * Generate and download the PDF file.
     *
     * @param string $filename
     * @return mixed
     */
    public function download($filename = 'document.pdf');

    /**
     * Stream the generated PDF directly to the browser.
     *
     * @param string $filename
     * @return mixed
     */
    public function stream($filename = 'document.pdf');
}
