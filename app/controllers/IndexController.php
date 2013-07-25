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
}