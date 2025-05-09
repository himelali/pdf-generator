<?php

namespace Tests\Unit\Drivers;

use Himelali\PdfGenerator\Exceptions\PdfGenerationException;
use Mpdf\MpdfException;
use PHPUnit\Framework\TestCase;
use Himelali\PdfGenerator\Drivers\MpdfDriver;
use Himelali\PdfGenerator\Exceptions\RenderException;

class MpdfDriverTest extends TestCase
{
    public function testInitialization()
    {
        $driver = new MpdfDriver();
        $this->assertInstanceOf(MpdfDriver::class, $driver);
    }

    /**
     * @throws MpdfException
     * @throws PdfGenerationException
     */
    public function testSetPageSize()
    {
        $driver = new MpdfDriver();
        $driver->setPageSize('A4');
        $this->assertTrue(true);  // No exception should be thrown
    }

    /**
     * @throws MpdfException
     * @throws PdfGenerationException
     */
    public function testSetMargins()
    {
        $driver = new MpdfDriver();
        $driver->setMargins(10, 10, 10, 10);
        $this->assertTrue(true);  // No exception should be thrown
    }

    public function testSetHeader()
    {
        $driver = new MpdfDriver();
        $driver->setHeader('<h1>Header</h1>');
        $this->assertTrue(true);
    }

    public function testSetFooter()
    {
        $driver = new MpdfDriver();
        $driver->setFooter('<h1>Footer</h1>');
        $this->assertTrue(true);
    }

    public function testLoadHtml()
    {
        $driver = new MpdfDriver();
        $html = '<h1>Test HTML</h1>';
        $driver->loadHtml($html);
        $this->assertTrue(true);
    }

    /**
     * @throws PdfGenerationException
     */
    public function testDownload()
    {
        $driver = new MpdfDriver();
        $pdf_content = $driver->download('test.pdf');
        $this->assertNotEmpty($pdf_content);
    }

    /**
     * @throws PdfGenerationException
     */
    public function testStream()
    {
        $driver = new MpdfDriver();
        $pdf_content = $driver->stream('test.pdf');
        $this->assertNotEmpty($pdf_content);
    }

    /**
     * @throws PdfGenerationException
     */
    public function testRenderException()
    {
        $this->expectException(RenderException::class);
        $driver = new MpdfDriver();
        $driver->download('invalid-path.pdf');
    }
}
