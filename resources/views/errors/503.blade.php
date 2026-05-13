@extends('errors::minimal')

@section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))
@section('code', '503')
@section('message', __('Service Unavailable'))
