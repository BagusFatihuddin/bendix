@props(['product'])

<article class="flex flex-col gap-3 pb-4">

  {{-- IMAGE (NO ASPECT, NO HEIGHT FORCE) --}}
  <div class="relative w-full rounded-2xl overflow-hidden bg-gray-100">
    @php
      $img = $product->image_url ?? null;
      $placeholder = asset('images/placeholder.png');
      $svgFallback = "data:image/svg+xml;utf8,".rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400">
          <rect width="100%" height="100%" fill="#e5e7eb"/>
          <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
            fill="#9ca3af" font-family="Arial,sans-serif" font-size="20">
            No Image
          </text>
        </svg>'
      );
    @endphp

    <img
      loading="lazy"
      decoding="async"
      src="{{ $img ?? $placeholder }}"
      alt="{{ $product->name }}"
      class="w-full h-auto block"
      onerror="this.onerror=null; this.src='{{ $svgFallback }}';"
    />

    {{-- WISHLIST --}}
    <button
      class="absolute top-3 right-3
             w-7 h-7 rounded-full
             bg-white/90 backdrop-blur
             flex items-center justify-center
             shadow-sm"
      aria-label="Tambah ke wishlist">

      <div class="w-3.5 h-3.5 text-blue-600">
        @include('icons.heart')
      </div>
    </button>
  </div>

  {{-- TITLE & CATEGORY --}}
  <div class="flex flex-col gap-1 px-1">
    <h3 class="text-sm font-semibold text-gray-900 leading-snug line-clamp-2">
      {{ $product->name }}
    </h3>

    <p class="text-xs text-gray-500">
      {{ $product->category->name ?? '' }}
    </p>
  </div>

  {{-- PRICE & RATING --}}
  <div class="flex items-center justify-between px-1">
    <span class="text-sm font-semibold text-gray-800">
      Rp {{ number_format(
        is_numeric($product->price)
          ? $product->price
          : ($product->price_per_day ?? 0),
        0, ',', '.'
      ) }}/day
    </span>

    <div class="flex items-center gap-1">
      <div class="w-3.5 h-3.5 text-yellow-400">
        @include('icons.start')
      </div>
      <span class="text-xs text-gray-800">
        {{ number_format($product->rating ?? 5, 1) }}
      </span>
    </div>
  </div>

</article>
