@extends('errors::minimal')

@section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))
@section('code', '429')
@section('message', __('Too Many Requests'))
