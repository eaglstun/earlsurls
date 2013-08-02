<?

class IndexController extends BaseController {

	/*
	*	home page
	*/
	public function indexAction(){
		return View::make( 'home' );
	}

	/*
	*	process url and shorten
	*/
	public function postAction(){
		$url = Input::get( 'url' );
		$parsed = array_merge( array(
			'scheme' => '',
			'host' => '',
			'path' => '',
			'query' => ''
		), parse_url($url) );
		
		// see if this exists
		$res = DB::table( 'urls' )
				->where( 'scheme', '=' , $parsed['scheme'] )
				->where( 'host', '=' , $parsed['host'] )
				->where( 'path', '=' , $parsed['path'] )
				->where( 'query', '=' , $parsed['query'] )
				->first();
				
		// insert if doesnt exist
		if( !$res ){
			$id = DB::table( 'urls' )->insertGetId(
			    array( 'scheme' => $parsed['scheme'], 
			    	   'host' => $parsed['host'],
			    	   'path' => $parsed['path'],
			    	   'query' => $parsed['query'] )
			);
		} else {
			$id = $res->id;
		}
			
		$r = new Base;
		$short = $r->convertToBase( $id );
		
		$short = url().'/'.$short;
		$chars_original = strlen( $url );
		$chars_short = strlen( $short );
		
		return View::make( 'post' )
				->with( 'chars_diff', $chars_original - $chars_short )
				->with( 'chars_original', $chars_original )
				->with( 'chars_short', $chars_short )
				->with( 'diff_percent', round( ($chars_short / $chars_original) * 100, 2) )
				->with( 'original', $url )
				->with( 'short', $short );
	}
	
	/*
	*	get long url and redirect
	*/
	public function redirectAction( $code ){
		$r = new Base;
		$id = $r->convertToBase10( $code );
		
		$res = DB::table( 'urls' )
				->where( 'id', '=', $id )
				->first();
		
		// not using http_build_url() here because of shared hosting… 
		// @TODO detect if installed and use if is
		$url = $res->scheme.'://'.$res->host.$res->path;
		if( trim($res->query) )
			$url .= '?'.$res->query;
		
		// @TODO tracking	
		return Redirect::to( $url );
	}
}