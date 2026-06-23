<?php
$directories = ['resources/views', 'app/Http/Controllers'];
foreach ($directories as $dir) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getRealPath());
            if (strpos($content, '#BK-') !== false || strpos($content, '#BK') !== false) {
                $content = str_replace('#BK-', '#IMK-', $content);
                $content = str_replace('#bk-', '#imk-', $content);
                $content = str_replace('#BK', '#IMK', $content);
                $content = str_replace('#bk', '#imk', $content);
                file_put_contents($file->getRealPath(), $content);
                echo 'Updated ' . $file->getRealPath() . PHP_EOL;
            }
        }
    }
}
