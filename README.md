<p align="center">
  <img src="https://img.shields.io/packagist/dt/himelali/pdf-generator" alt="Total Downloads"/>
  <img src="https://img.shields.io/github/license/himelali/pdf-generator" alt="License"/>
  <img src="https://img.shields.io/github/stars/himelali/pdf-generator" alt="Stars"/>
  <img src="https://img.shields.io/github/forks/himelali/pdf-generator" alt="Forks"/>
</p>

# 📄 PdfGenerator for Laravel

PdfGenerator is a flexible and extensible PDF generation wrapper for Laravel that supports multiple drivers including **DomPDF**, **Snappy (wkhtmltopdf)**, **mPDF**, and **FPDF** — all with a unified interface.

---

## 🚀 Features

- Multiple driver support: `dompdf`, `snappy`, `mpdf`, `fpdf`
- Unified API for all drivers
- Configurable driver options
- Easily extendable with custom drivers
- Laravel service provider and config publishing
- Supports UTF-8, HTML5, CSS3, custom fonts, header/footer, margins, and more

---

## 📦 Installation

```bash
composer require himelali/pdf-generator
```

## ⚙️ Configuration

### 🧪 Laravel Version Compatibility

Depending on your Laravel version, register the service provider as follows:

#### Laravel 11+

Edit `bootstrap/providers.php`:

```php
return [
    // Other providers...
    \Himelali\PdfGenerator\PdfServiceProvider::class,
];
```
#### Laravel 10 and below
```php
'providers' => [
    // Other providers...
    \Himelali\PdfGenerator\PdfServiceProvider::class,
],
```
For older versions, register the alias manually in `config/app.php`:
```php
'aliases' => Facade::defaultAliases()->merge([
    'Pdf' => Himelali\PdfGenerator\Facades\Pdf::class,
])->toArray(),
```
⚠️ If Facade::defaultAliases() doesn't exist in your Laravel version, just use:
```php
'aliases' => [
    // Other aliases...
    'Pdf' => Himelali\PdfGenerator\Facades\Pdf::class,
],
```

#### The service provider will register the PDF manager and allow you to publish the config file using:
```bash
php artisan vendor:publish --tag=config
```

This will publish `config/pdf-generator.php`. Example configuration:


```php
return [
    'default' => env('PDF_DRIVER', 'dompdf'),

    'drivers' => [
        'dompdf' => [
            'options' => [
                'is_html5_parser_enabled' => true,
                'is_php_enabled' => true,
            ],
        ],
        'snappy' => [
            'binary' => env('SNAPPY_PDF_BINARY', '/usr/local/bin/wkhtmltopdf'),
            'options' => [
                'page-size' => 'A4',
                'margin-top' => '10mm',
                'margin-bottom' => '10mm',
                'footer-left' => 'Your Company Name',
                'footer-center' => '[page]',
                'footer-right' => 'Confidential',
            ],
        ],
        'mpdf' => [
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'temp_dir' => storage_path('app/mpdf/temp'),
            'font_dir' => storage_path('app/pdf/fonts'),
        ],
        'fpdf' => [
            'orientation' => 'P',
            'unit' => 'mm',
            'format' => 'A4',
            'font_dir' => storage_path('app/pdf/fonts'),
        ],
    ],
];
```

## 🧩 Supported Drivers

| Driver | Package Dependency                                   | Binary Required |
| ------ |------------------------------------------------------| --------------- |
| dompdf | `dompdf/dompdf`                                      | ❌               |
| snappy | `knplabs/knp-snappy` + `wkhtmltopdf` binary [link](https://github.com/KnpLabs/snappy) | ✅               |
| mpdf   | `mpdf/mpdf`                                          | ❌               |
| fpdf   | `setasign/fpdf`                                      | ❌               |


Install the required dependencies based on your driver.


```php
use Pdf;

$html = '<h1>Hello PDF</h1>';
Pdf::loadHtml($html)->download('hello.pdf');
```

```php
Pdf::setPageSize('A4')
    ->setMargins(10, 15, 10, 15)
    ->setHeader('<p>Header HTML</p>')
    ->setFooter('<p>Footer HTML</p>')
    ->loadHtml('<h1>With Headers and Margins</h1>')
    ->download('report.pdf');
```

## 📂 Available Methods
| Method             | Description                                 |
| ------------------ | ------------------------------------------- |
| `setPageSize()`    | Set paper size (e.g., A4, Letter)           |
| `setMargins()`     | Set page margins (top, right, bottom, left) |
| `setHeader()`      | Set HTML for header                         |
| `setFooter()`      | Set HTML for footer                         |
| `setFooterText()`  | Set simple text footer (fallback)           |
| `setPageNumbers()` | Enable or disable page numbers              |
| `setFonts()`       | Load custom fonts                           |
| `loadHtml()`       | Load HTML content                           |
| `download()`       | Download the PDF                            |
| `stream()`         | Stream the PDF in browser                   |


```php
Pdf::extend('custom', \App\Pdf\CustomPdfDriver::class);
Pdf::driver('custom')->loadHtml('<h1>Custom Driver</h1>')->download();
```

## ❗Exceptions
| Exception Class           | When it’s Thrown                                                     |
| ------------------------- |----------------------------------------------------------------------|
| `BinaryNotFoundException` | [wkhtmltopdf](https://wkhtmltopdf.org/) binary missing (Snappy only) |
| `InvalidDriverException`  | Driver not found in config or map                                    |
| `PdfGenerationException`  | General rendering failure                                            |
| `RenderException`         | Library-specific render issues                                       |


## ✅ Requirements
- Laravel 6+
- PHP 7.1 or higher
- At least one supported PDF library installed ([dompdf](https://dompdf.github.io), [mpdf](https://mpdf.github.io), [knplabs/knp-snappy](https://github.com/KnpLabs/snappy), or [setasign/fpdf](https://www.fpdf.org/))

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.txt).

Please note: This is a wrapper around the following libraries, each of which is maintained under its own license:

- [dompdf/dompdf](https://github.com/dompdf/dompdf) – [LGPL-2.1-only](https://github.com/dompdf/dompdf/blob/master/LICENSE.LGPL)
- [mpdf/mpdf](https://github.com/mpdf/mpdf) – [GNU GPL v2](https://github.com/mpdf/mpdf/blob/development/LICENSE.txt)
- [knplabs/knp-snappy](https://github.com/KnpLabs/KnpSnappy) – [MIT License](https://github.com/KnpLabs/KnpSnappy/blob/master/LICENSE)
- [setasign/fpdf](http://www.fpdf.org/) – [Free to use, non-copyleft](http://www.fpdf.org/)

Make sure to review and comply with each library’s license if you use this package in your projects.
