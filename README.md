# 📄 PdfGenerator for Laravel

`Himelali\PdfGenerator` is a flexible and extensible PDF generation wrapper for Laravel that supports multiple drivers including **DomPDF**, **Snappy (wkhtmltopdf)**, **mPDF**, and **FPDF** — all with a unified interface.

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

## 📖 License

This package is open-sourced software licensed under the MIT license.
