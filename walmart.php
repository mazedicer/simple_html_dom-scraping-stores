<?php
include_once( "./classes/Walmart.php" );
$walmart_scraper = new Walmart();
$nstrike_result = $walmart_scraper->scrape_NSTRIKE( 'http://www.walmart.com/search/?query=nerf&cat_id=0&grid=true', 'Nerf N-Strike' );
echo $nstrike_result;
