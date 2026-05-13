@extends('errors::minimal')

@section('title', ucwords(str_replace('/', ' ', request()->getRequestUri())))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
