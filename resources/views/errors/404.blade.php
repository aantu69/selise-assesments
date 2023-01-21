@extends('errors.minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __($exception->getMessage() ?: 'The page you are looking for not found.'))
