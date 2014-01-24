<?php
header('Content-disposition: attachment; filename=samplefile.xls');
header('Content-type:application/xls');
readfile('samplefile.xls');
?>