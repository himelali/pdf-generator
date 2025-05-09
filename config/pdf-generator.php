<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default PDF Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default PDF driver that will be used.
    | Supported: "dompdf", "snappy", "mpdf", "fpdf"
    |
    */

    'default' => env('PDF_DRIVER', 'dompdf'),

    /*
    |--------------------------------------------------------------------------
    | PDF Drivers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many PDF "drivers" as you wish.
    | Each driver has its own specific settings.
    |
    */

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
                'no-outline' => true,
                'images' => true,
                'zoom' => 1.0,
                'custom-header' => [
                    'Content-Type' => 'application/pdf;charset=UTF-8',
                ],
                'page-size' => 'A4',
                'margin-top' => '10mm',
                'margin-bottom' => '10mm',
                'footer-left' => '', // Your Company Name
                'footer-center' => '[page]/[topage]',
                'footer-right' => '', // Confidential
            ],
        ],

        'mpdf' => [
            'mode' => '',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'temp_dir' => storage_path('app/mpdf/temp'),
            'font_dir' => storage_path('app/pdf/fonts'),
            'default_font' => 'dejavusans',
            'default_font_size' => 12,
        ],

        'fpdf' => [
            'orientation' => 'P',
            'unit' => 'mm',
            'format' => 'A4',
            'font_dir' => storage_path('app/pdf/fonts'),
        ],

    ],
];
