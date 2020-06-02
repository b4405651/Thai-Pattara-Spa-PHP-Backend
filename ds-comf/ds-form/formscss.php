<?php
header("Content-type: text/css; charset: UTF-8");
foreach (glob("css/*.css") as $filename) {
    echo "\n".'/*/ds-comf/ds-form/'.$filename.'*/'."\n";
    echo file_get_contents($filename);
}
?>
