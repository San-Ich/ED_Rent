@extends('layouts.app')
@section('title', 'KudaBesiRent | Profile')
@section('content')
<x-detail-profile :user="$user" />
@endsection