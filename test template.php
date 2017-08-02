<?php
include_once( "./classes/Test.php" );
$scraper = new Test();
$result = $scraper->scrape( 'URL' );
echo $result;
