<?php
include_once( "./classes/Target.php" );
$toysrus_scraper = new Target();

$nstrike_result = $toysrus_scraper->scrape_NSTRIKE( 'http://www.target.com/c/toy-blasters-outdoor-toys/-/N-5xtaa', 'Nerf N-Strike' );
echo $nstrike_result;


