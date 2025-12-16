
{{-- =================================================================== --}}
@extends('front.layouts.app')

@section('title', $product->name . ' - Bendix')

@section('content')
<div class="mx-auto max-w-[375px]">

  {{-- ================= FLOATING HEADER ================= --}}
<div class="fixed top-0 left-0 right-0 z-50 pointer-events-none">
  <div class="mx-auto max-w-[375px] px-4 pt-4">
    <div class="flex items-center justify-between">

      {{-- Back button --}}
      <button
        onclick="history.back()"
        aria-label="Kembali"
        class="pointer-events-auto flex h-10 w-10 items-center justify-center
               rounded-full bg-white/70 backdrop-blur-md
               shadow-lg hover:opacity-80"
      >
        <svg
          class="h-6 w-6 text-blue-600"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2.5"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="m15 18-6-6 6-6"></path>
        </svg>
      </button>

      {{-- Wishlist --}}
      <button
        aria-label="Wishlist"
        class="pointer-events-auto flex h-10 w-10 items-center justify-center
               rounded-full bg-white/70 backdrop-blur-md
               shadow-lg hover:opacity-80"
      >
        <svg
          class="h-6 w-6 text-blue-600"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#004CFF"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"></path>
        </svg>
      </button>

    </div>
  </div>
</div>


  {{-- ================= HERO IMAGE ================= --}}
  <div class="relative w-full bg-gray-100 pt-8">
    <div class="mx-auto px-4">

      @php
        // kumpulkan image list: thumbnail_url + photos->url
        $images = collect();

        if (!empty($product->thumbnail_url)) {
          $images->push($product->thumbnail_url);
        }

        $photos = $product->relationLoaded('photos')
          ? $product->photos
          : $product->photos()->get();

        foreach ($photos as $p) {
          if (!empty($p->url)) {
            $images->push($p->url);
          } elseif (!empty($p->thumbnail_url)) {
            $images->push($p->thumbnail_url);
          }
        }

        if ($images->isEmpty()) {
          $images->push(asset('images/placeholder.png'));
        }

        $firstImage = $images->first();
      @endphp

      {{-- HERO CARD --}}
      {{-- <div class="relative rounded-3xl bg-white p-3 shadow-sm"> --}}

        {{-- IMAGE --}}
<div class="relative aspect-[4/5] overflow-hidden rounded-2xl bg-gray-100 flex items-center justify-center">
  <img
    id="product-hero"
    src="{{ $firstImage }}"
    alt="{{ $product->name }}"
    class="max-h-full max-w-full object-contain"
    loading="lazy"
    decoding="async"
  />
</div>

        {{-- DOTS --}}
        <div class="mt-3 flex justify-center gap-2">
          <div class="h-2 w-8 rounded-full bg-blue-600"></div>
          @for ($i = 1; $i < $images->count(); $i++)
            <div class="h-2 w-2 rounded-full bg-blue-200"></div>
          @endfor
        </div>
      {{-- </div> --}}

      {{-- THUMBNAILS --}}
      {{-- <div class="mt-4">
        <div class="flex gap-3 overflow-x-auto pb-2">
          @foreach($images as $idx => $img)
            <button
              type="button"
              class="thumb-btn h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border-2
                     {{ $idx === 0 ? 'border-blue-600' : 'border-gray-200' }}"
              data-src="{{ $img }}"
            >
              <img
                src="{{ $img }}"
                alt="thumb-{{ $idx }}"
                class="h-full w-full object-cover"
                loading="lazy"
              />
            </button>
          @endforeach
        </div>
      </div> --}}

    </div>
  </div>



@push('scripts')
<script>
  (function () {
    const hero = document.getElementById('product-hero');
    const thumbs = document.querySelectorAll('.thumb-btn');

    thumbs.forEach(btn => {
      btn.addEventListener('click', function () {
        const src = this.getAttribute('data-src');
        if (!src) return;

        hero.setAttribute('src', src);

        thumbs.forEach(t => t.classList.remove('border-blue-600'));
        this.classList.add('border-blue-600');
      });
    });
  })();
</script>
@endpush


{{-- ===================================================================== --}}
  <!-- CONTENT -->
  <div class="px-4 pt-6 pb-[120px]">
    <!-- Title & price -->
    <div class="mb-6 flex flex-col gap-3">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h1>

        <div class="mt-3 flex flex-wrap items-center gap-3">
          <div class="flex items-center gap-1">
            <svg class="h-4 w-4 fill-green-500 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700">5.00</span>
            <span class="text-sm text-gray-500">139 Trips</span>
          </div>
        </div>
      </div>

      <div class="text-2xl font-semibold text-gray-900">
        Rp {{ number_format($product->price, 0, ',', '.') }}
        <span class="text-base font-normal text-gray-500">/day</span>
      </div>
    </div>

    <!-- Description (about) -->
    <div class="mb-6">
      <h2 class="mb-2 text-lg font-semibold text-gray-900">Description</h2>
      <p class="text-sm text-gray-700 whitespace-pre-line">
        {{ $product->about }}
      </p>
    </div>

<!-- Insurance (static) -->
<div class="mb-6">
  <div class="flex items-center gap-2 mb-2">
    <h2 class="text-lg font-semibold text-gray-900">Insurance</h2>
  </div>

  <div class="flex items-start gap-3 rounded-2xl border border-gray-200 p-4">
    <div class="flex-shrink-0 text-blue-600 mt-4">
      @include('icons.perisai')
    </div>

    <div class="flex-1">
      <p class="text-sm font-medium text-gray-900">
        Insurance Via Travelers
      </p>
      <button class="mt-1 text-sm underline text-gray-900">
        Read More
      </button>
    </div>
  </div>
</div>


<!-- Cancellation (static) -->
<div class="mb-6">
  <h2 class="mb-2 text-lg font-semibold text-gray-900">
    Cancellation Policy
  </h2>

  <div class="flex items-start gap-3 rounded-2xl border border-gray-200 p-4">
    <div class="flex-shrink-0 text-blue-600 mt-2">
      @include('icons.jempol')
    </div>

    <div class="flex-1">
      <p class="text-sm text-gray-600">
        Free Cancellation. If you cancel your booking before 5 days of your trip.
      </p>
    </div>
  </div>
</div>


    <!-- Rating summary (static) -->
    <div class="mb-6">
      <h2 class="mb-3 text-lg font-semibold text-gray-900">Rating and Reviews</h2>
      <div class="mb-4 flex items-center gap-2">
        <span class="text-lg font-medium text-gray-900">5.00</span>
        <svg class="h-5 w-5 fill-blue-600 text-blue-600" viewBox="0 0 24 24" fill="none"><path d="M11.525 2.295..."></path></svg>
        {{-- <span class="text-sm text-gray-900">(4 ratings)</span> --}}
      </div>

      <!-- static rating bars -->
      <div class="space-y-3 mb-4">
        <div class="flex items-center gap-3">
          <span class="w-28 text-sm text-gray-600">Cleanliness</span>
          <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full" style="width:98%"></div>
          </div>
          <span class="w-8 text-right text-sm text-gray-600">4.9</span>
        </div>

        <div class="flex items-center gap-3">
          <span class="w-28 text-sm text-gray-600">Maintenance</span>
          <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full" style="width:98%"></div>
          </div>
          <span class="w-8 text-right text-sm text-gray-600">4.9</span>
        </div>

        <div class="flex items-center gap-3">
          <span class="w-28 text-sm text-gray-600">Communication</span>
          <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full" style="width:95%"></div>
          </div>
          <span class="w-8 text-right text-sm text-gray-600">4.8</span>
        </div>

        <div class="flex items-center gap-3">
          <span class="w-28 text-sm text-gray-600">Communication</span>
          <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full" style="width:85%"></div>
          </div>
          <span class="w-8 text-right text-sm text-gray-600">4.6</span>
        </div>

        <div class="flex items-center gap-3">
          <span class="w-28 text-sm text-gray-600">Listing accuracy</span>
          <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full" style="width:98%"></div>
          </div>
          <span class="w-8 text-right text-sm text-gray-600">4.9</span>
        </div>
      </div>
    </div>

    <!-- Reviews (static examples) -->
    <div class="mb-4">
      {{-- <div class="mb-3 flex items-center justify-between">
        <div>
          <span class="font-medium text-gray-900">Reviews</span>
          <span class="ml-2 text-sm text-gray-900">(56 reviews)</span>
        </div>
        <button class="text-sm text-gray-500 hover:text-gray-700">View All</button>
      </div> --}}

      {{-- <div class="space-y-4">
        <div class="rounded-3xl border border-gray-200 bg-gray-50 p-4">
          <div class="mb-3 flex items-center gap-3">
            <img src="https://api.builder.io/api/v1/image/assets/TEMP/77abb62380603df54b40e280e54240b34a9388e4?width=64" alt="Felicia" class="h-8 w-8 rounded-full object-cover"/>
            <div>
              <p class="text-sm font-medium text-gray-900">Felicia</p>
              <p class="text-xs text-gray-500">20 July 2023</p>
            </div>
          </div>
          <div class="mb-2 flex gap-1">
            <!-- star icon -->
            <svg class="h-4 w-4 fill-blue-600 text-blue-600" viewBox="0 0 24 24"><path d="M11.525 2.295..."></path></svg>
            <!-- repeater -->
          <p class="text-sm text-gray-600">Lorem Ipsum ...</p>
        </div>

        <!-- second static revew) -->
      </div>
    </div> --}}

    <!-- Guidelines -->
    <div class="mb-4">
      <h2 class="mb-2 text-lg font-semibold text-gray-900">Guidelines</h2>
      <ul class="space-y-2">
        <li class="text-sm text-gray-600">• NO PETS and NO SMOKING are Strictly Enforced with this rental</li>
        <li class="text-sm text-gray-600">If you are a smoker... <button class="ml-1 underline">Read More</button></li>
      </ul>
    </div>
  </div>
</div>

<!-- BOTTOM CTA -->
<div class="fixed bottom-0 left-0 right-0 bg-transparent z-40">
  <div class="max-w-[375px] mx-auto bg-white border-t border-[#DFDEDE] px-4 py-3">

    <div
      class="flex items-center justify-between gap-4 px-5 py-3 rounded-2xl border-2 border-blue-600 bg-white"
      style="
        box-shadow:
          rgba(0, 76, 255, 0.10) 0px 40px 60px,
          rgba(0, 76, 255, 0.15) 0px 20px 40px,
          rgba(0, 76, 255, 0.20) 0px 10px 20px,
          rgb(0, 76, 255) 0px 4px 0px;
      "
    >
      {{-- Price --}}
      <div class="flex flex-col leading-tight">
        <span class="text-xs text-gray-500 font-medium">
          Starting from
        </span>
        <span class="text-lg font-bold text-gray-900">
          Rp {{ number_format($product->price, 0, ',', '.') }}
        <span class="text-base font-normal text-gray-500">/day</span>
        </span>

      </div>

      {{-- CTA --}}
      <a
        href="{{ route('front.booking', $product->slug) }}"
        class="bg-blue-600 text-white font-bold py-2.5 px-8 rounded-xl
               hover:bg-blue-700 active:scale-95 transition text-sm"
      >
        Rent Now
      </a>
    </div>

  </div>
</div>

@endsection
