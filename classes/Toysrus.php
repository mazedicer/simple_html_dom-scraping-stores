<?php
include_once('./simple_html_dom.php');
class Toysrus {

public function scrape_NSTRIKE($url, $title) {
    // create HTML DOM
    $html = file_get_html($url);
	$header = '<a href="' . $url . '" target="_blank" >' . $title . '</a><br>';
	$main_template = file_get_contents( './templates/toysrus/blaster_main.txt' );
	$template = file_get_contents( './templates/toysrus/blaster_results.txt' );
	$main_replace = array( '{title}', '{content}' );
	$replace = array( '{name}', '{img}', '{price}' );
    // get overview
    foreach($html->find('div[class="prodloop_cont"]') as $div) {
		// get name
		$name = $div->children(0)->children(3)->plaintext;
		// get image
		foreach( $div->find( 'div[class="prodloop-thumbnail"]' ) as $thumbnl ){
			if( $thumbnl->children(0)->children(1) ){
				$img = '<img width="100" src="http://www.toysrus.com' . $thumbnl->children(0)->children(1)->src . '">';
			}else{
				$img = '<img width="100" src="http://www.toysrus.com' . $thumbnl->children(0)->children(0)->src . '">';
			}//if
		}//foreach
		// get price
		foreach($div->find('div[class="prodPrice familyPrices"]') as $prod_price) {
		    if( $prod_price->children(1) ){
				$price = $prod_price->children(1)->plaintext;
			}else{
				$price = $prod_price->plaintext;
			}//if
            
        }//foreach
		$replace_with = array( $name, $img, $price );
		$result .= str_replace( $replace, $replace_with, $template );
		
    }//foreach
	$main_replace_with = array( $header, $result );
	$main_result = str_replace( $main_replace, $main_replace_with, $main_template );
	return $main_result;
    // clean up memory
    $html->clear();
    unset($html);
}//scrape_NSTRIKEg_NSTRIKE

}//Toysrus


