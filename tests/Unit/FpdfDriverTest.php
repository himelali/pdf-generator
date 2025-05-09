<?php

namespace Tests\Unit\Drivers;

use PHPUnit\Framework\TestCase;
use Himelali\PdfGenerator\Drivers\FpdfDriver;
use Himelali\PdfGenerator\Exceptions\RenderException;

class FpdfDriverTest extends TestCase
{
    public function testInitialization()
    {
        $driver = new FpdfDriver();
        $this->assertInstanceOf(FpdfDriver::class, $driver);
    }

    public function testSetPageSize()
    {
        $driver = new FpdfDriver();
        $driver->setPageSize('A4');
        $this->assertTrue(true);  // No exception should be thrown
    }

    public function testSetMargins()
    {
        $driver = new FpdfDriver();
        $driver->setMargins(10, 10, 10, 10);
        $this->assertTrue(true);  // No exception should be thrown
    }

    public function testSetHeader()
    {
        $driver = new FpdfDriver();
        $driver->setHeader('<h1>Header</h1>');
        $this->assertTrue(true);
    }

    public function testSetFooter()
    {
        $driver = new FpdfDriver();
        $driver->setFooter('<h1>Footer</h1>');
        $this->assertTrue(true);
    }

    public function testLoadHtml()
    {
        $driver = new FpdfDriver();
        $html = '<h1>Test HTML</h1>';
        $driver->loadHtml($html);
        $this->assertTrue(true);
    }

    public function testDownload()
    {
        $driver = new FpdfDriver();
        $pdf_content = $driver->download('test.pdf');
        $this->assertNotEmpty($pdf_content);
    }

    public function testStream()
    {
        $driver = new FpdfDriver();
        $pdf_content = $driver->stream('test.pdf');
        $this->assertNotEmpty($pdf_content);
    }

    public function testRenderException()
    {
        $this->expectException(RenderException::class);
        $driver = new FpdfDriver();
        $driver->download('invalid-path.pdf');
    }
}
