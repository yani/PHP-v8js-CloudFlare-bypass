<?php
/*
	This is a CloudFlare bypass that makes use of the v8 javascript engine for PHP, which emulates javascript.
	The V8 engine does not come with a DOM, so we still have to change the javascript around to do it without one.
		
	The CF bypass function takes a cloudflare browser-check html page and returns an array with the values.
	
	Array
	(
		[jschl_vc] => 9a49ded879936321313e133f2d0f5019
		[pass] => 1464738243.714-T2IlAZnumuN
		[jschl_answer] => 14137
	) 
	
	How to install v8js php module:
		- https://blog.xenokore.com/how-to-install-v8js-for-php-on-linux/
		- https://blog.xenokore.com/how-to-install-v8js-for-php-on-windows/
	
	Created by Yani
		- https://github.com/Yanikore
		- https://blog.xenokore.com/
*/

function CFBypass ($cf_page){
    try { $v8 = new V8Js();
        if(strpos($cf_page, 's,t,o,p,b,r,e,a,k,i,n,g,f, ') !== false){
            function gb($content, $start, $end){$r = explode($start, $content); if (isset($r[1])){$r = explode($end, $r[1]); return $r[0];} return '';}
            $domain_length = strlen(gb($cf_page, 'before accessing</span> ', '.</h1>')); $line1 = gb($cf_page, 's,t,o,p,b,r,e,a,k,i,n,g,f, ', ';') . ';';
            $line2 = strstr(gb($cf_page, "document.getElementById('challenge-form');", 't.length;') . $domain_length . ';', $var = explode('=', $line1)[0]);
            $ret = $v8->executeString('var ' . $line1 . str_replace('a.value', '$', $line2)); // Yani's CF bypass of pleasure and leisure
            if(is_numeric($ret)) return ['jschl_vc' => gb($cf_page, 'jschl_vc" value="', '"'), 'pass' => gb($cf_page, 'pass" value="', '"'), 'jschl_answer' => $ret];
        } } catch(Exception $ex) { /*1.0*/ }
    return false;
}

/***********************************/
/**** Example usage in a script ****/
/***********************************/

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://example.com/',
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.84 Safari/537.36',
    CURLOPT_COOKIEJAR => '' // Needed to keep cookies in curlhandle
]);
$res = curl_exec($ch);

if($cf = CFBypass($res)){
    Sleep(4);
    curl_setopt_array($ch, [
        CURLOPT_URL => 'http://example.com/cdn-cgi/l/chk_jschl?jschl_vc=' . $cf['jschl_vc'] . '&pass=' . $cf['pass'] . '&jschl_answer=' . $cf['jschl_answer'],
        CURLOPT_REFERER => 'http://example.com/'
    ]);
    $res = curl_exec($ch);
}

echo $res;

// Made by Yani