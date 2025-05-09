<?php

namespace Himelali\PdfGenerator;

use Himelali\PdfGenerator\Contracts\PdfDriverInterface;
use Himelali\PdfGenerator\Drivers\DomPdfDriver;
use Himelali\PdfGenerator\Drivers\FpdfDriver;
use Himelali\PdfGenerator\Drivers\MpdfDriver;
use Himelali\PdfGenerator\Drivers\SnappyDriver;
use InvalidArgumentException;

class PdfManager
{
    /**
     * The configuration array for PDF drivers.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The current driver instance.
     *
     * @var PdfDriverInterface|null
     */
    protected $instance = null;

    /**
     * Available PDF drivers and their corresponding class mappings.
     *
     * @var array
     */
    protected $drivers = [
        'dompdf' => DomPdfDriver::class,
        'snappy' => SnappyDriver::class,
        'mpdf' => MpdfDriver::class,
        'fpdf' => FpdfDriver::class,
    ];

    /**
     * Create a new PdfManager instance.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
        $default_driver = $config['default'] ?? null;
        $this->driver($default_driver);
    }

    /**
     * Get a driver instance by name.
     *
     * If no name is provided, the default driver from config is used.
     *
     * @param string|null $name
     * @return PdfDriverInterface
     *
     * @throws InvalidArgumentException
     */
    public function driver(string $name = null): PdfDriverInterface
    {
        $name = $name ?? ($this->config['default'] ?? null);
        if (! isset($this->drivers[$name])) {
            throw new InvalidArgumentException("PDF driver [{$name}] is not supported.");
        }

        $config = $this->config['drivers'][$name] ?? [];
        $driver = app($this->drivers[$name], ['config' => $config]);
        $this->instance = $driver;

        return $driver;
    }

    /**
     * Dynamically forward method calls to the default driver instance.
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (! $this->instance) {
            $this->driver();
        }

        return call_user_func_array([$this->instance, $method], $arguments);
    }

    /**
     * Extend the manager with a custom driver.
     *
     * @param string $name
     * @param string $driver_class
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function extend(string $name, string $driver_class): void
    {
        if (! in_array(PdfDriverInterface::class, class_implements($driver_class))) {
            throw new InvalidArgumentException("The driver [{$driver_class}] must implement PdfDriverInterface.");
        }

        $this->drivers[$name] = $driver_class;
    }
}
