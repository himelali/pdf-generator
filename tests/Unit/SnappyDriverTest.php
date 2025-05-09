<?php

namespace Tests\Unit\Drivers;

use PHPUnit\Framework\TestCase;
use Himelali\PdfGenerator\Drivers\SnappyDriver;
use Himelali\PdfGenerator\Exceptions\RenderException;

class SnappyDriverTest extends TestCase
{
    public function testInitialization()
    {
        $driver = new SnappyDriver();
        $this->assertInstanceOf(SnappyDriver::class, $driver);
    }

    public function testSetPageSize()
    {
        $driver = new SnappyDriver();
        $driver->setPageSize('A4');
        $this->assertTrue(true);  // No exception should be thrown
    }

    public function testSetMargins()
    {
        $driver = new SnappyDriver();
        $driver->setMargins(10, 10, 10, 10);
        $this->assertTrue(true);  // No exception should be thrown
    }

    public function testSetHeader()
    {
        $driver = new SnappyDriver();
        $driver->setHeader('<h1>Header</h1>');
        $this->assertTrue(true);
    }

    public function testSetFooter()
    {
        $driver = new SnappyDriver();
        $driver->setFooter('<h1>Footer</h1>');
        $this->assertTrue(true);
    }

    public function testLoadHtml()
    {
        $driver = new SnappyDriver();
        $html = '<h1>Test HTML</h1>';
        $driver->loadHtml($html);
        $this->assertTrue(true);
    }

    public function testDownload()
    {
        $driver = new SnappyDriver();
        $pdf_content = $driver->download('test.pdf');
        $this->assertNotEmpty($pdf_content);
    }

    public function testStream()
    {
        $driver = new SnappyDriver();
        $pdf_content = $driver->stream('test.pdf');
        $this->assertNotEmpty($pdf_content);
    }

    public function testRenderException()
    {
        $this->expectException(RenderException::class);
        $driver = new SnappyDriver();
        $driver->download('invalid-path.pdf');
    }
}
