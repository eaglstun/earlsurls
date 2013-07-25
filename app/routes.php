<?

Route::get( '/', 'IndexController@indexAction' );
Route::get( '{any}', 'IndexController@redirectAction' );
Route::post( 'post', 'IndexController@postAction' );

/*
*	static pages
*/
Route::get( 'help', function(){
	return View::make( 'help' );
} );