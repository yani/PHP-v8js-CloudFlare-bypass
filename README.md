PHP v8js CloudFlare Bypass
--------------------------

This is a CloudFlare bypass that makes use of the v8 javascript engine for PHP, which emulates javascript.

The V8 engine does not come with a DOM, so we still have to change the javascript around to do it without one.

### Usage
The CF bypass function takes a cloudflare browser-check html page and returns an array with the values.
```
Array
(
	[jschl_vc] => 9a49ded879936321313e133f2d0f5019
	[pass] => 1464738243.714-T2IlAZnumuN
	[jschl_answer] => 14137
)  
```

### Notes
You'll have to Sleep() and do all HTTP requests yourself. This function just calculates all required variables by feeding it the HTML source of the CloudFlare "Checking Your Browser..." page. The function and example are found in cf-bypass.php.

If you are accessing a subdomain, the function will be unable to grab the length of the domain and you'll have to pass it manually.
Cloudflare uses the length of the domain in its calculation, and only the top domain can be grabbed from just the source. 

Remember that the v8js php module is required to emulate the javascript. You can find blog posts on how to install the module below.

### How to install v8js php module (required)
- https://blog.xenokore.com/how-to-install-v8js-for-php-on-linux/
- https://blog.xenokore.com/how-to-install-v8js-for-php-on-windows/