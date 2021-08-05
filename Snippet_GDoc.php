function parse_style($header,$style,$main)
{
	//fix links
	//href="https://www.google.com/url?q=URL&..." => href="URL"
	$look_behind = '(?<=href=")'; 								//keep href=" prefix
	$url_google = 'https://www.google.com/url?q=';				//google url
	$url_google_quote = preg_quote($url_google,'/');			//special symbols quoted
	$url_real = '([^&]*(?=&))';									//real url (will be $1) and is searched upto '&' lookahead
	$suffix = '&[^"]*(?=")';									//rest starting from '&' upto '"' lookahead
	$pattern = '/'.$look_behind.$url_google_quote.$url_real.$suffix.'/';
	//$main = preg_replace($pattern,'$1',$main);
	$main = preg_replace_callback($pattern,
								  	function($matches) {
								  	//change %25 to % in $1
								  		$res = preg_replace('/\%25(?=[0-9a-fA-F]{2})/','%',$matches[1]);
										$res = preg_replace('/\%23/','#',$res);
										$res = preg_replace('/\%3D/','=',$res);
										$res = preg_replace('/\%7B/','{',$res);
										$res = preg_replace('/\%7D/','}',$res);
										return $res;
								  	},
								  $main);

	//add additional gdoc class to all elements with class
	//class="xxx yyy" => class="xxx yyy gdoc"
	$main = preg_replace('/class="[^"]*(?=")/','$0 gdoc"',$main);

	//remove @import directive
	//NB! general pattern to remove css directives is @[^;]*;
	$style = preg_replace('/@import[^;]*;/','',$style);
	//add white table borders
	$style = substr_replace($style, 'table,th,td{border:1px solid #cccccc}', 0, 0);
	
	//split style into selectors and attributes
	$res = preg_split('/({[^{}]*})/',$style,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
	//DBG $dbg1 = '';
	foreach($res as $key => $value)
	{
		//DBG $dbg2 = '';
		if ($res[$key][0]=='{')
		{
			$attrs = $res[$key];
			//process attributes
			$attrs = preg_replace('/(?<=[{;])background-color:[^;}]*(;|(?=}))/','',$attrs); //removes background-color attribute
			$attrs = preg_replace('/(?<=[{;])color:[^;}]*(;|(?=}))/','',$attrs);			//removes color attribute
			$attrs = preg_replace('/(?<=[{;])max-width:[^;}]*(;|(?=}))/','',$attrs);		//removes max-width attribute
			$attrs = preg_replace('/(?<=[{;])padding:[^;}]*(;|(?=}))/','',$attrs);          //removes padding attribute
			
			/* DBG
			if (strpos($res[$key],'color')!==false)
			{
				$arr = explode(';',substr($attrs, 1, strlen($attrs)-2));				        //explode to array of individual attributes without {}
				$dbg2 = $dbg2.'#'.$key.'#'.$res[$key].'<br>';									//original attributes
				foreach($arr as $index => $val)
				{
					$dbg2 = $dbg2.'____#'.$index.'#'.$val.'<br>'; 								//fixed attributes
				}
			}*/
			
			$res[$key] = $attrs;
		}
		else
		{
			//process selectors
			//DBG $fixed = false;
			$sels = explode(',',$res[$key]);
			foreach($sels as $index => $val)
			{
				//NB! there is no special code for space>+~ combinators and for * selector
				if (strpos($val,':before')!==false) continue;
				if (strpos($val,'lst-kix')!==false) continue;
				//if (strpos($val,'.')!==false) continue; //there is class already
				if (strpos($val,'#')!==false) continue; //there is id already
				$sels[$index] = $sels[$index].'.gdoc';
				//DBG $fixed = true;
			}
			$res[$key] = implode(',',$sels);
			//DBG if ($fixed) $dbg2 = $key.":".$res[$key].'<br>';
		}
		//DBG $dbg1 = $dbg1.$dbg2;
	}
	$style = implode($res);
	
	return /*DBG '<div>'.$dbg1.'</div>'.*/ $header.$style.$main;
}

function parse_gdoc($html_content) 
{
	//simple check for current gdoc structure
	//if (strpos($html_content, 'DOCS_installLinkReferrerSanitizer()') === false)
	//	return '<H1>Error: invalid gdoc format (1)</H1>';	
	
	// find main content
	$pos1 = strpos($html_content, '<div id="contents">');
	$pos2 = strpos($html_content, '<div id="footer">');
	if (($pos1 === false) || ($pos2 === false))
		return '<H1>Error: invalid gdoc format (2)</H1>';	
	$content = substr($html_content, $pos1, $pos2 - $pos1);
	
	//find style block
	$pos1 = strpos($content, '<style');
	$pos2 = strpos($content, '</style>');
	if (($pos1 === false) || ($pos2 === false))
		return '<H1>Error: invalid gdoc format (3)</H1>';
	$pos1 = strpos($content, '>', $pos1);
	if ($pos1 === false)
		return '<H1>Error: invalid gdoc format (4)</H1>';
	$pos1++;
	
	$header = substr($content, 0, $pos1);				//up to the end of <style ...>	- nothing to change
	$style = substr($content, $pos1, $pos2 - $pos1);	//style content					- fix css style
	$main = substr($content, $pos2);					//starting with </style>		- fix classes
	
	return parse_style($header,$style,$main);
}

function sr_gdoc_func( $atts ) {
	$atts = shortcode_atts( array(
		'url' => '',
	), $atts, 'sr_gdoc' );
	$url = $atts['url'];
	
	if ($url=='')
		return '<H1>Error: missing gdoc url</H1>';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$html_content = curl_exec($ch);
	curl_close($ch);
	
	if ($html_content === false)
		return '<H1>Error: invalid gdoc url (0) </H1>';
	
	return parse_gdoc($html_content);
}
add_shortcode( 'sr_gdoc', 'sr_gdoc_func' );