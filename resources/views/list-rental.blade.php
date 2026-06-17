@vite(['resources/css/list-rental.css'])

@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Daftar Sewa')
@section('content')
    <x-header-list-rental />
    <x-filter-bar :counts="$counts" />
    <x-order-list :orders="$rentals" />
    <x-syarat />
@endsection

@vite(["resources/js/list-rental.js"])
@vite(["resources/js/order-modal.js"])

