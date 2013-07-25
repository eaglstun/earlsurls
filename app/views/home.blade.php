@extends('layout')

@section('content')
	<form action="/post" id="shorten_form" method="post">
		<fieldset>
			<input type="text" class="input" id="url" name="url" tabindex="1" value="http://"/>
			
			<input type="submit" class="button" tabindex="3" value="Shorten" />
		</fieldset>
	</form>
@stop