@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Home')
@section('content')
    <x-hero />
    <x-search-widget />
    <x-carousel />
    <x-popular-card :motors="$popularMotors" />
    <x-features />
    <x-syarat />
@endsection

