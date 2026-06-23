<?php
$file = 'c:\\laragon\\www\\imakostudio\\.env';
$content = file_get_contents($file);
// Remove any weird encoding characters added by powershell
$content = preg_replace('/BROADCAST_CONNECTION=reverb.*$/s', "BROADCAST_CONNECTION=reverb\nAPP_TIMEZONE=Asia/Jakarta\n", $content);
file_put_contents($file, $content);
echo "Fixed .env file";
