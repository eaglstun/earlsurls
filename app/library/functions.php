<? 

/*
*
*	@return string
*/
function randomlink(){
	$urls = array( 
					'http://en.wikipedia.org/wiki/URL_shortening#Criticism_and_problems',
					'http://en.wikipedia.org/w/index.php?title=URL_shortening&action=edit',
					'http://en.wikipedia.org/wiki/TinyURL#Early_abuses',
					'http://marnanel.org/tinyurl-whacking',
					'http://tinyurl.com/8e4',
					'http://www.shadyurl.com/',
					'http://5z8.info/oneweirdoldtiptolosebellyfat_c5e5k_girlsgonewildpart1.wmv',
					'http://www.opsi.gov.uk/about/accessibility.htm',
					'http://www.toprankblog.com/2009/01/11-best-url-shortening-services-vote-your-favorite/',
					'http://www.hugeurl.com/',
					'http://is.gd/aifGq'
				 );
	$rand = array_rand($urls);
	return $urls[$rand];
}