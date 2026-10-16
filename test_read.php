<?php
$p = __DIR__.'/sitemap.xml';
header('Content-Type: text/plain; charset=UTF-8');
echo "size=".filesize($p)."\n\n";
readfile($p);