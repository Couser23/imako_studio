<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$directories = ['resources/views', 'app/Http/Controllers'];
foreach ($directories as $dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getRealPath());
            $original = $content;
            
            // Blade patterns
            $content = preg_replace('/#IMK-\{\{\s*str_pad\(\$([a-zA-Z0-9_]+)\->id,\s*4,\s*\'0\',\s*STR_PAD_LEFT\)\s*\}\}/', '#IMK-{{ $$1->booking_code }}', $content);
            $content = preg_replace('/#IMK-\{\{\s*str_pad\(\$([a-zA-Z0-9_]+)\->booking\->id,\s*4,\s*\'0\',\s*STR_PAD_LEFT\)\s*\}\}/', '#IMK-{{ $$1->booking->booking_code }}', $content);

            // PHP string concatenation patterns
            $content = preg_replace('/\'#IMK-\'\s*\.\s*str_pad\(\$([a-zA-Z0-9_]+)\->id,\s*4,\s*\'0\',\s*STR_PAD_LEFT\)/', "'#IMK-' . $$1->booking_code", $content);
            
            // Hardcoded strings like #IMK-0820
            $content = str_replace('#IMK-0821', '#IMK-XXXXXX', $content);
            $content = str_replace('#IMK-0820', '#IMK-YYYYYY', $content);
            $content = str_replace('#IMK-0831', '#IMK-ZZZZZZ', $content);
            
            // Activity log patterns
            $content = preg_replace('/Booking #IMK-\{\$payment\->booking_id\}/', 'Booking #IMK-{$payment->booking->booking_code}', $content);

            if ($original !== $content) {
                file_put_contents($file->getRealPath(), $content);
                echo 'Updated ' . $file->getRealPath() . PHP_EOL;
            }
        }
    }
}
