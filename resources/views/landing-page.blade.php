@extends('layouts.app')

@section('content')
    <x-hero />
    <x-search-widget />
    <x-carousel />
    <x-popular-card :motors="$popularMotors" />
    <x-features />
    <x-syarat />
@endsection

