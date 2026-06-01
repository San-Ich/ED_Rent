@vite(['resources/css/list-rental.css'])

@extends('layouts.app')
@section('title', 'KudaBesiRent | Daftar Sewa')
@section('content')
    <x-header-list-rental />
    <x-filter-bar :counts="$counts" />
    <x-order-list :orders="$rentals" />
    <x-syarat />
@endsection

@vite(["resources/js/list-rental.js"])

