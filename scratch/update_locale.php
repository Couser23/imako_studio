<?php
$file = 'c:\\laragon\\www\\imakostudio\\.env';
$content = file_get_contents($file);

$content = str_replace('APP_LOCALE=en', 'APP_LOCALE=id', $content);
$content = str_replace('APP_FALLBACK_LOCALE=en', 'APP_FALLBACK_LOCALE=id', $content);
$content = str_replace('APP_FAKER_LOCALE=en_US', 'APP_FAKER_LOCALE=id_ID', $content);

file_put_contents($file, $content);
echo "Updated locales in .env to id";
