@vite(['resources/css/catalog.css'])
@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Pilihan Kendaraan')
@section('content')
    <x-header-catalog />
    <x-search-box />
    <x-category-filter />
    <x-card-catalog :motors="$motors" />
    <x-syarat />
@endsection
