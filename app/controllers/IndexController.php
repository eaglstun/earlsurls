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
		return View::make( 'post' );
	}
	
	/*
	*	get long url and redirect
	*/
	public function redirectAction( $code ){
		$r = new Base;
		$id = $r->convertToBase10( $code );
		
		var_dump( $id );
	}
}