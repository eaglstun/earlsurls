<?

use Illuminate\Database\Migrations\Migration;

class Urls extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(){
		Schema::create('urls', function($table){
	        $table->increments('id');
	        $table->string('scheme');
	        $table->string('host');
	        $table->string('path');
	        $table->string('query');
	        $table->timestamp('added_on');
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(){
		//
	}

}