<?php
include_once( "./classes/Toysrus.php" );
$toysrus_scraper = new Toysrus();

$nstrike_result = $toysrus_scraper->scrape_NSTRIKE( 'http://www.toysrus.com/family/index.jsp?categoryId=28760506&cp=28760476&ppg=500', 'Nerf N-Strike' );
echo $nstrike_result;

$nerf_result = $toysrus_scraper->scrape_NSTRIKE( 'http://www.toysrus.com/family/index.jsp?categoryId=75711616&ppg=500', 'Nerf Blasters' );
echo $nerf_result;

$rival_result = $toysrus_scraper->scrape_NSTRIKE( 'http://www.toysrus.com/family/index.jsp?categoryId=70928626&ppg=500', 'Nerf Rival' );
echo $rival_result;

$boomco_result = $toysrus_scraper->scrape_NSTRIKE( 'http://www.toysrus.com/family/index.jsp?categoryId=35330626&sr=1&origkw=boomco&ppg=500', 'BoomCo.' );
echo $boomco_result;

$statsblast_result = $toysrus_scraper->scrape_NSTRIKE( 'http://www.toysrus.com/family/index.jsp?categoryId=75711426&ppg=500', 'Stats Blast' );
echo $statsblast_result;

$statsblast_result = $toysrus_scraper->scrape_NSTRIKE( 'http://www.toysrus.com/search/index.jsp?kwCatId=&kw=stats%20blast&keywords=stats%20blast&origkw=stats+blast&sr=1&ppg=500', 'Stats Blast' );
echo $statsblast_result;