@extends('errors::minimal')

@section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))
@section('code', '402')
@section('message', __('Kasir Required'))
