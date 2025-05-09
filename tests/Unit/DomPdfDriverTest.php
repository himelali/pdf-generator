<?php

namespace Tests\Unit\Drivers;

use PHPUnit\Framework\TestCase;
use Himelali\PdfGenerator\Drivers\DomPdfDriver;
use Himelali\PdfGenerator\Exceptions\RenderException;

class DomPdfDriverTest extends TestCase
{
    public function testInitialization()
    {
        $driver = new DomPdfDriver();
        $this->assertInstanceOf(DomPdfDriver::class, $driver);
    }

    public function testSetPageSize()
    {
        $driver = new DomPdfDriver();
        $driver->setPageSize('A4');
        $this->assertTrue(true);  // No exception should be thrown
    }

    public function testSetMargins()
    {
        $driver = new DomPdfDriver();
        $driver->setMargins(10, 10, 10, 10);
        $this->assertTrue(true);  // No exception should be thrown
    }

    public function testSetHeader()
    {
        $driver = new DomPdfDriver();
        $driver->setHeader('<h1>Header</h1>');
        $this->assertTrue(true);
    }

    public function testSetFooter()
    {
        $driver = new DomPdfDriver();
        $driver->setFooter('<h1>Footer</h1>');
        $this->assertTrue(true);
    }

    public function testLoadHtml()
    {
        $driver = new DomPdfDriver();
        $html = '<h1>Test HTML</h1>';
        $driver->loadHtml($html);
        $this->assertTrue(true);
    }

    public function testDownload()
    {
        $driver = new DomPdfDriver();
        $driver->download('test.pdf');
        $this->assertTrue(true);
    }

    public function testStream()
    {
        $driver = new DomPdfDriver();
        $driver->stream('test.pdf');
        $this->assertTrue(true);
    }

    public function testRenderException()
    {
        $this->expectException(RenderException::class);
        $driver = new DomPdfDriver();
        $driver->download('invalid-path.pdf');
    }
}
