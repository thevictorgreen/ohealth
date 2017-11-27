<?php


$pdf_file = escapeshellarg( "rtaylor.pdf" );
$jpg_file = escapeshellarg( "rtaylor.jpg" );

exec( "convert $pdf_file $jpg_file");
echo "We made it Roger Troutman!!!";



?>
