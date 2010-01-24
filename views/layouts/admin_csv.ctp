<?php 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($content_for_layout));
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=export.csv");
echo $content_for_layout 
?> 