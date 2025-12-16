@extends('front.layouts.app')

@section('title', 'Bendix - Beranda')

@section('content')
@php
$heroAds = [
  [
    'title' => 'Easy Rental',
    'subtitle' => 'Ready-to-use equipment',
    'image_url' => 'https://res.cloudinary.com/dshhlawvf/image/upload/v1765723009/Gemini_Generated_Image_au6639au6639au66_ussnti.png',
  ],
  [
    'title' => 'Low Prices',
    'subtitle' => 'No hidden fees',
    'image_url' => 'https://res.cloudinary.com/dshhlawvf/image/upload/v1765723011/Gemini_Generated_Image_7e8e657e8e657e8e_dqd48u.png',
  ],
  [
    'title' => 'Top Quality',
    'subtitle' => 'Professional standard',
    'image_url' => 'https://res.cloudinary.com/dshhlawvf/image/upload/v1765723010/Gemini_Generated_Image_fo0x6nfo0x6nfo0x_thzkv7.png',
  ],
  [
    'title' => 'Fast Booking',
    'subtitle' => 'Ready in seconds',
    'image_url' => 'https://res.cloudinary.com/dshhlawvf/image/upload/v1765723009/Gemini_Generated_Image_teu39nteu39nteu3_cm3let.png',
  ],
  [
    'title' => 'Pickup & Drop',
    'subtitle' => 'We handle everything',
    'image_url' => 'https://res.cloudinary.com/dshhlawvf/image/upload/v1765723009/Gemini_Generated_Image_omxsoiomxsoiomxs_vwgpdk.png',
  ],
];
@endphp




<div class="relative overflow-hidden">

  {{-- ================= TOP BACKGROUND (BUBBLES) ================= --}}
  <div class="absolute top-0 left-0 right-0 h-56 z-0">
    <img
      src="https://res.cloudinary.com/dshhlawvf/image/upload/v1765685089/Bubbles_tim70j.png"
      alt=""
      class="w-full h-full object-cover"
      aria-hidden="true"
    />
  </div>

  {{-- ================= HEADER (TRANSPARENT) ================= --}}
  <header class="relative z-20 bg-transparent">
    <div class="mx-auto max-w-[375px]">
      <div class="flex items-center justify-between px-4 py-3">

        {{-- Logo --}}


        <a
          href="{{ route('front.index') }}"
          class="inline-flex items-center gap-2"
          aria-label="Beranda"
        >
          {{-- Logo wrapper --}}
          <span class="inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-white/20 transition">
            <svg viewBox="0 0 37 37" class="w-8 h-8">
              <path
                {{-- d="M3.08993 23.0795C5.00224 26.45 6.52691 25.7641 9.50294 23.0795..." --}}
              d="M3.08993 23.0795C5.00224 26.45 6.52691 25.7641 9.50294 23.0795C10.9297 21.1714 11.6511 20.0556 12.7094 17.9311C14.5828 14.8261 14.9405 12.7957 17.6649 10.0964C20.3894 7.39711 24.7082 6.94003 26.2642 10.0964C25.9389 12.0716 25.326 12.9476 23.7864 14.2749L21.5273 16.2149C20.6426 17.1769 20.6276 17.7254 21.5273 18.7518L23.5678 20.0949C25.0697 20.9414 25.5613 21.3591 26.0456 22.0349C26.6269 23.4724 26.4953 24.1105 26.0456 25.1688C25.367 26.569 24.7326 27.1752 23.2034 28.0042C20.8331 29.1771 19.8595 29.1434 18.6852 28.0042C17.97 26.9129 17.8193 26.2906 17.7378 25.1688C17.9973 22.1945 18.2787 20.5199 19.0496 17.4834C19.3008 16.2818 19.3157 15.7584 19.0496 15.1703C18.5329 14.5586 18.1363 14.69 17.3734 15.1703C14.6702 19.4093 13.4502 21.932 11.9078 26.7357C11.1755 29.2983 10.9884 30.7274 11.9078 33.2272C15.8581 38.4901 27.4627 38.0195 32.8958 33.2272C38.3289 28.435 38.4071 23.773 32.8958 20.0949L29.7622 17.6326C32.2747 16.6505 33.6137 15.9522 35.3736 13.3795C36.4963 11.4099 36.6932 10.2098 35.3736 7.70872C33.6531 5.36221 32.1702 4.59704 28.8877 3.90333C24.8284 2.94954 22.4696 2.93028 18.1022 3.90333C11.8651 6.40408 9.15627 8.48503 5.64046 13.3795C3.23779 17.2353 2.60963 19.3637 3.08993 23.0795Z"
                fill="#004CFF"
              />
            </svg>
          </span>

          {{-- Text (no padding) --}}
          <h3 class="text-xl font-bold leading-none pb-0" style="color: #004CFF;">
            Bendix
          </h3>
        </a>



        {{-- Notification --}}
        <button
          aria-label="Notifikasi"
          class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/90 backdrop-blur border border-white/40 hover:bg-white transition"
        >
          <svg viewBox="0 0 16 16" class="w-5 h-5 text-blue-600" fill="none">
            <path
              d="M7.99992 11.8981C11.7594 11.8981 13.4986 11.4158 13.6666 9.48001C13.6666 7.54554 12.454 7.66994 12.454 5.29642C12.454 3.44243 10.6967 1.33301 7.99992 1.33301C5.3031 1.33301 3.54582 3.44243 3.54582 5.29642C3.54582 7.66994 2.33325 7.54554 2.33325 9.48001C2.50189 11.4231 4.2411 11.8981 7.99992 11.8981Z"
              stroke="currentColor"
              stroke-width="1.5"
            />
            <path
              d="M9.59241 13.9043C8.68299 14.9141 7.26431 14.9261 6.34619 13.9043"
              stroke="currentColor"
              stroke-width="1.5"
            />
          </svg>
        </button>

      </div>
    </div>
  </header>

  {{-- ================= FADE CONNECTOR ================= --}}
  <div class="relative z-10 h-10"></div>

  {{-- ================= MAIN CONTENT ================= --}}
  <div class="relative z-10 bg-transparent">

    {{-- ================= HERO / ADS ================= --}}

<div
  x-data="{
    active: 0,
    timer: null,
    scrollTo(index) {
      const track = this.$refs.track
      const cardWidth = track.children[0].offsetWidth + 16
      track.scrollTo({
        left: cardWidth * index,
        behavior: 'smooth'
      })
      this.active = index
    },
    start() {
      this.timer = setInterval(() => {
        this.scrollTo((this.active + 1) % {{ count($heroAds) }})
      }, 4000)
    },
    stop() {
      clearInterval(this.timer)
    }
  }"
  x-init="start()"
  class="px-4 -mt-6"
>


  {{-- VIEWPORT --}}
  <div
    x-ref="track"
    class="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory
           scrollbar-hide"
    @scroll.debounce.100ms="
      active = Math.round($el.scrollLeft / ($el.scrollWidth / {{ count($heroAds) }}))
    "
  >

    @foreach($heroAds as $ad)
      {{-- CARD --}}
      <div class="min-w-[100%] snap-center">

        <div class="relative h-32 rounded-xl overflow-hidden shadow-lg" style="background-color: #f7b723">
          <img
            src="{{ $ad['image_url'] }}"
            class="absolute inset-0 w-full h-full object-cover"
            alt="{{ $ad['title'] }}"
          />


          <div class="absolute inset-0"></div>

          <div class="relative z-10 h-full flex flex-col justify-between p-4">
            <div>
              <h3 class="text-2xl font-bold text-white leading-tight">
                {{ $ad['title'] }}
              </h3>
              <p class="text-xs font-semibold text-white mt-1">
                {{ $ad['subtitle'] }}
              </p>
            </div>

            <a
              href="#"
              class="self-end bg-white/90 text-blue-600 text-xs font-semibold px-3 py-1 rounded-md"
            >
              Lihat Detail
            </a>
          </div>
        </div>

      </div>
    @endforeach

  </div>

  {{-- DOTS --}}
  <div class="flex justify-center gap-2 mt-2">
    @foreach($heroAds as $i => $_)
      <button
        @click="scrollTo({{ $i }})"
        class="w-2 h-2 rounded-full"
        :class="active === {{ $i }} ? 'bg-blue-600' : 'bg-blue-300'"
      ></button>
    @endforeach
  </div>

</div>

    {{-- ================= CATEGORIES ================= --}}
    @include('front.components.categories', ['categories' => $categories ?? collect()])

    {{-- ================= PRODUCTS ================= --}}
<section class="px-4 mb-20">
  <h2 class="text-2xl font-bold text-gray-900 mb-4">
    Just For You
  </h2>

  {{-- MASONRY CONTAINER --}}
  <div class="columns-2 gap-4 space-y-4">
    @foreach($products as $product)
      <a href="{{ route('front.details', $product->slug) }}"
         class="block break-inside-avoid">
        @include('front.components.product-card', ['product' => $product])
      </a>
    @endforeach
  </div>

  {{-- PAGINATION --}}
  <div class="mt-8">
    {{ $products->links() }}
  </div>
</section>


  </div>
</div>
@endsection
