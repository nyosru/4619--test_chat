@extends('layouts.app')

@section('content')

room blade php

	@if (Auth::check())
		<div class="container">
			<private-chat :room="{{$room ?? 1 }}" :user="{{Auth::user()}}"></private-chat>
		</div>
	@endif

@endsection
