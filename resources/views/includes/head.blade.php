<meta charset="utf-8" />
<title>{{ config('app.name') }} | @yield('title')</title>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
<meta content="" name="uraian" />
<meta content="" name="author" />

<link rel="icon" href="/assets/img/favicon.png" type="image/gif">

<!-- ================== BEGIN BASE CSS STYLE ================== -->
<link href="{{ config('app.aset_cdn_url') }}/assets/css/vendor.min.css" rel="stylesheet" />
<link href="{{ config('app.aset_cdn_url') }}/assets/css/app.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- ================== END BASE CSS STYLE ================== -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select-picker@0.3.2/dist/picker.min.css" rel="stylesheet">

@stack('css')
