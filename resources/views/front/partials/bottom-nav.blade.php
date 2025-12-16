<nav
  class="fixed bottom-4 left-0 right-0 z-50 flex justify-center"
  aria-label="Navigasi bawah"
>
  <div class="w-full max-w-[360px] px-4">
    <div class="bg-[#eef5ff] rounded-full h-[64px] flex items-center justify-between px-4 shadow-lg">

      {{-- BERANDA --}}
      <a
        href="{{ route('front.index') }}"
        class="flex items-center justify-center w-12 h-12 rounded-full transition
          {{ request()->routeIs('front.index')
              ? 'bg-white text-blue-600'
              : 'bg-gray-300 text-gray-400' }}"
      >
        @include('icons.home')
      </a>

      {{-- CEK PESANAN --}}
      <a
        {{-- href="{{ route('orders.lookup') }}" --}}
        href="{{ route('front.transactions') }}"
        class="flex items-center justify-center w-12 h-12 rounded-full transition
          {{ request()->routeIs('front.transactions')
              ? 'bg-white text-blue-600'
              : 'bg-gray-300 text-gray-400' }}"
      >
        @include('icons.orders')
      </a>

      {{-- PROMO / DEALS --}}
      <a
        {{-- href="{{ route('deals.index') }}" --}}
        href="{{ route('front.reels') }}"
        class="flex items-center justify-center w-12 h-12 rounded-full transition
          {{ request()->routeIs('front.reels')
              ? 'bg-white text-blue-600'
              : 'bg-gray-300 text-gray-400' }}"
      >
        @include('icons.reels')
      </a>

      {{-- CHAT / BANTUAN --}}
      <a
        {{-- href="{{ route('support.chat') }}" --}}
        href="{{ route('global.chat') }}"
        class="flex items-center justify-center w-12 h-12 rounded-full transition
          {{ request()->routeIs('global.chat')
              ? 'bg-white text-blue-600'
              : 'bg-gray-300 text-gray-400' }}"
      >
        @include('icons.chat')
      </a>

    </div>
  </div>
</nav>
