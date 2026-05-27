@extends('layouts.app')
@section('tittle', 'KudaBesiRent - Detail Motor')
@section('content')
    <x-detail-motor :motor="$motor" />
    <x-card-detail-rekomendasi :rekomendasiMotors="$rekomendasiMotors" />
    <x-syarat />
@endsection
