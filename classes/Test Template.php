<?php
include_once('./simple_html_dom.php');
class Test {

public function scrape_NSTRIKE($url, $title) {
	$curl = curl_init(); 
	curl_setopt( $curl, CURLOPT_URL, $url );  
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );  
	curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 10 );  
	$str = curl_exec( $curl );  
	curl_close($curl);  
	// create HTML DOM
    $html = str_get_html($str);
	//$main_template = file_get_contents( './templates/walmart/blaster_main.txt' );
	//$template = file_get_contents( './templates/walmart/blaster_results.txt' );
	//$main_replace = array( '{title}', '{content}' );
	//$replace = array( '{name}', '{img}', '{price}' );
    // get overview
    
		//$replace_with = array( $name, $img, $price );
		//$result .= str_replace( $replace, $replace_with, $template );
		
    
	//$main_replace_with = array( $header, $result );
	//$main_result = str_replace( $main_replace, $main_replace_with, $main_template );
	
	
	
	
	return $main_result;
    // clean up memory
    $html->clear();
    unset($html);
}//scrape_NSTRIKEg_NSTRIKE

}//Toysrus


