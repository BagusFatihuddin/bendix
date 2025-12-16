<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Bendix')</title>

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
  />
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('head')
</head>

<body class="bg-[#F4F6FB] overflow-x-hidden">

@php
  $showBottomNavRoutes = [
      'front.index',
      'front.transactions',
      'front.reels',
      // 'global.chat',
  ];

  $showBottomNav = in_array(
      optional(Route::current())->getName(),
      $showBottomNavRoutes
  );
@endphp

{{-- DESKTOP WRAPPER --}}
<div class="min-h-screen flex justify-center">

  {{-- APP CANVAS --}}
  <div
    class="w-full max-w-[420px] min-h-screen bg-white relative
           shadow-[0_0_40px_rgba(0,0,0,0.08)]
           {{ $showBottomNav ? 'pb-24' : '' }}"
  >

    <main class="w-full min-h-screen">
      @yield('content')
    </main>

    @if ($showBottomNav)
      @include('front.partials.bottom-nav')
    @endif

  </div>
</div>

@stack('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
