@props(['dir'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$dir ? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    
    <!-- Enhanced viewport for ALL devices with responsive support -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    
    <!-- Mobile browser optimizations -->
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#28a745">
    
    <!-- Prevent text size adjustment on mobile -->
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    
    <!-- Responsive design meta tags -->
    <meta name="HandheldFriendly" content="true">
    <meta name="MobileOptimized" content="320">
    
    <!-- Favicon for different devices -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}"
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Home | {{ config('app.name') }}</title>

    @include('partials.dashboard._head')
</head>
<body class="" >
@include('partials.dashboard._body')
</body>

</html>
