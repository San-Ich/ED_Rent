@extends('layouts.app')
@section('tittle', 'KudaBesiRent | Payment Success')
@section('content')
<x-detail-payment-success :rental="$rental" />
@endsection