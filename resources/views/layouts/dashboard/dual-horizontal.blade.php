@props(['dir'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$dir ? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    
    <!-- Enhanced viewport for ALL devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    
    <!-- Mobile browser optimizations -->
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#28a745">
    <meta name="apple-mobile-web-app-title" content="{{env('APP_NAME')}}">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{env('APP_NAME')}} | Responsive Bootstrap 5 Admin Dashboard Template</title>

    @include('partials.dashboard._head')
</head>
<body class="" >
@include('partials.dashboard._body3')
</body>
</html>