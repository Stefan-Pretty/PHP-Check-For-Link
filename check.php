<?php

	/**
	*	
	*	CHECK IF LINK IS ON PAGE TO SEPCIFIC DOMAIN
	*	@author Stefan Pretty
	*	@version  1.0
	*
	**/

	/*
	==========================
	REQUIRED FUNCTIONS
	==========================
	*/

	function get_data($url) {

		$ch = curl_init();

		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Pretty Klicks Bot');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;

	}

	function fetch_url($url){

		if(is_callable('curl_init')){

			$result =get_data($url);
			
			if(!empty($result)){
				return $result;
			}else{
				return false;
			}

		}else{

			ini_set('user_agent', 'Pretty Klicks Bot');
			$result = file_get_contents($url);

			if($result !== false){

				return $result;

			}else{

				return false;

			}

		}

	}


	/*
	==========================
	RUN SCRIPT
	==========================
	*/

	//grab the URL to check and the URL to test for
	if(!isset($_GET['url']) || !isset($_GET['test_url'])){

		echo "Error: Missing parameters.";
		exit;

	}

	$url = $_GET['url'];
	$test_url = $_GET['test_url'];

	/**
	*	@todo Add URL validation for input if being passed via HTTP request as per this example;
	**/

	//get the content of the page
	if(!$content = fetch_url($url)){

		echo "No content found at destination URL";
		exit;

	}

	$dom = new DomDocument();
	$dom->loadHTML($content);
	$urls = $dom->getElementsByTagName('a');

	$exists = false;

	for ($n = 0; $n < $urls->length; $n++) {
	    $item = $urls->item($n);
	    $href = $item->getAttribute('href');
	    
	    if(stristr($href, $test_url)){
	    	$exists = true;
	    }

	}

	if($exists){
		echo "true";
		//return true;
	}else{
		echo "false";
		//return false;
	}

	exit;


?>