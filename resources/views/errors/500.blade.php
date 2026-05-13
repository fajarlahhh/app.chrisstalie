@extends('errors::minimal')

@section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))
@section('code', '500')
@section('message', __('Server Error'))
