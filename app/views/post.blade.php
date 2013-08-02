@extends('layout')

@section('content')
<p>( control+c or right click/copy to copy to your clipboard )</p>

<input type="text" id="short_url" class="input post" onkeyup="select_text();" value="{{ $short }}" readonly="readonly"/>

<p>
	Your EARLS URLS.biz SHORT URL is 
	<a href="{{ $short }}">{{ $short }}</a>
	( {{ $chars_short }} characters, case sensitive ). 
</p>

<p>
	The original URL was <a href="{{ $original }}">{{ $original }}</a>
	( {{ $chars_original }} characters ).
</p>

<p>
	Your URL is {{ $diff_percent }}% ( {{ $chars_diff }} characters ) shorter.
</p>
@stop