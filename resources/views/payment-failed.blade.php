@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Payment Failed')
@section('content')
<x-detail-payment-failed :rental="$rental" />
@endsection