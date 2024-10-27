@extends('layouts.default')

@section('content')

    <h1>
        Dashboard page
    </h1>
    
    @php
        use Illuminate\Support\Facades\Auth;

    @endphp

    <p>Logged username: <b>{{ Auth::user()->name }}</b></p>
@stop