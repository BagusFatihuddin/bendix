@extends('front.layouts.app')

@section('title', $category->name)

@section('content')
<div class="min-h-screen flex justify-center bg-slate-100">
  <div class="bg-white w-full max-w-[375px] min-h-screen flex flex-col">

    {{-- header --}}
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
      <div class="flex items-center justify-between px-4 py-3">
        <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 hover:bg-slate-50">
          {{-- back icon --}}
          <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>

                <h1 class="font-semibold text-lg text-center flex-1">
          {{ $category->name }}
        </h1>
{{-- agar di kaan list --}}
      <div class="flex justify-end">
      <button
        aria-label="List"
        class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md hover:opacity-80 text-blue-600"
      >
        @include('icons.list')
      </button>
    </div>
      </div>
    </header>

    <main class="px-4 py-6 flex-1">

<!-- =================================================== -->

{{-- TOP BRANDS --}}
<div class="mb-10">
  {{-- Header --}}
  <div class="flex items-center justify-between mb-5">
    <h2 class="text-lg font-semibold text-gray-900">
      Top Brands
    </h2>

    <a href="#"
       class="text-sm text-gray-400 hover:text-gray-900 transition">
      View All
    </a>
  </div>

  {{-- Brand Scroll --}}
  <div class="flex gap-6 overflow-x-auto pb-2 scrollbar-hide">

    {{-- ALL --}}
    <a href="{{ route('front.category', $category->slug) }}"
       class="group flex flex-col items-center min-w-max">

      <div
        class="relative w-16 h-16 rounded-full
               bg-white
               border border-gray-200
               flex items-center justify-center
               transition-all duration-300
               group-hover:scale-105
               group-hover:shadow-lg
               group-hover:border-gray-900">

        {{-- outer ring --}}
        <div class="absolute inset-0 rounded-full ring-1 ring-gray-100
                    group-hover:ring-gray-900/10"></div>

        {{-- icon --}}
        <div class="relative w-6 h-6 text-blue-600">
          @include('icons.all')
        </div>
      </div>

      <span class="mt-2 text-sm font-medium text-gray-800">
        All
      </span>
    </a>

    {{-- BRAND LIST --}}
    @foreach($brands as $b)
      <a href="{{ route('front.category.brand', [$category->slug, $b->slug]) }}"
         class="group flex flex-col items-center min-w-max">

        <div
          class="relative w-16 h-16 rounded-full
                 bg-white
                 border border-gray-200
                 overflow-hidden
                 transition-all duration-300
                 group-hover:scale-105
                 group-hover:shadow-lg
                 group-hover:border-gray-900">

          {{-- outer ring --}}
          <div class="absolute inset-0 rounded-full ring-1 ring-gray-100
                      group-hover:ring-gray-900/10"></div>

          <img
            src="{{ $b->logo_url ?? asset('images/brand-placeholder.png') }}"
            alt="{{ $b->name }}"
            class="relative w-full h-full object-contain p-3">
        </div>

        <span class="mt-2 text-sm font-medium text-gray-800">
          {{ $b->name }}
        </span>
      </a>
    @endforeach

  </div>
</div>
<!-- =================================================== -->

<!-- =================================================== -->

{{-- PRODUCT MASONRY --}}
<div class="mb-10">
  <h2 class="font-semibold mb-4 text-gray-900">
    Just for You
  </h2>

  {{-- MASONRY --}}
  <div class="columns-2 gap-4 space-y-4">
    @forelse($products as $product)
      <a href="{{ route('front.details', $product->slug) }}"
         class="break-inside-avoid block">

        {{-- CARD --}}
        <div class="flex flex-col gap-2">

          {{-- IMAGE --}}
          <div class="relative rounded-2xl overflow-hidden bg-gray-100">
            <img
              src="{{ $product->thumbnail_url }}"
              alt="{{ $product->name }}"
              class="w-full h-auto block">

            {{-- LIKE --}}
            <div
              class="absolute top-3 right-3
                     w-8 h-8 rounded-full
                     bg-white/90 backdrop-blur
                     flex items-center justify-center
                     shadow-sm">

              <div class="w-4 h-4 text-blue-600">
                @include('icons.heart')
              </div>
            </div>
          </div>

          {{-- INFO --}}
          <div class="flex flex-col gap-1 px-1">
            <p class="font-semibold text-sm leading-snug line-clamp-2">
              {{ $product->name }}
            </p>

            <p class="text-xs text-gray-400">
              {{ $product->brand->name }}
            </p>

            <div class="flex items-center justify-between mt-1">
              <span class="font-semibold text-sm">
                {{ $product->price }}/day
              </span>

              <div class="flex items-center gap-1 text-xs">
                <div class="w-3.5 h-3.5 text-yellow-400">
                  @include('icons.start')
                </div>
                <span>5.0</span>
              </div>
            </div>
          </div>

        </div>
      </a>
    @empty
      <p class="text-center text-gray-500">
        Produk belum tersedia
      </p>
    @endforelse
  </div>
</div>


<!-- =================================================== -->

    </main>
  </div>
</div>
@endsection
