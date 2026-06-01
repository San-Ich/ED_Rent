
@vite(['resources/css/catalog.css'])
@extends('layouts.app')
@section('title', 'KudaBesiRent | Pilihan Kendaraan')
@section('content')
    <x-header-catalog />
    <x-search-box />
    <x-category-filter />
    <x-card-catalog :motors="$motors" />
    <x-syarat />
@endsection
