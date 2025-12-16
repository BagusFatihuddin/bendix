{{-- resources/views/front/checkout.blade.php --}}
@extends('front.layouts.app') {{-- sesuaikan kalau layout lo beda --}}

@section('title', 'Checkout - ' . ($product->name ?? 'Product'))

@section('content')
@php
    // fallback jika booking payload belum lengkap
    // BELOM: pakai key yang sama dengan yang kita simpan di booking_save: booking.{product_id}
    $booking = $booking ?? session("booking." . ($product->id ?? '')) ?? null;

    $pricePerDay = isset($booking['price_per_day']) ? (int)$booking['price_per_day'] : (int)($product->price ?? 0);
    $days = isset($booking['days']) ? (int)$booking['days'] : 1;
    $unit = isset($booking['unit']) ? (int)$booking['unit'] : 1;
    $subtotal = $booking['subtotal'] ?? ($pricePerDay * $unit * $days);
    $ppn = $booking['ppn'] ?? (int) round($subtotal * 0.11);
    $insurance = $booking['insurance'] ?? 0;
    $grand_total = $booking['grand_total'] ?? ($subtotal + $ppn + $insurance);
    function money($n){ return 'Rp '.number_format($n,0,',','.'); }
@endphp


<div class="min-h-screen flex items-center justify-center bg-slate-100">
  <div class="bg-white w-full max-w-[375px] min-h-screen flex flex-col">
    {{-- header --}}
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
      <div class="flex items-center justify-between px-4 py-3">
        <button onclick="history.back()" class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 hover:bg-slate-50">
          {{-- back icon --}}
          <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m15 18-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>

        <h1 class="text-center font-semibold text-base text-slate-900">Checkout</h1>
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

    {{-- main --}}
    <main class="flex-1 px-4 py-5 pb-32">
      <form action="{{ route('front.checkout.store') }}" method="POST" enctype="multipart/form-data" id="checkout-form">
        @csrf

        <input type="hidden" name="product_id" value="{{ $product->id ?? '' }}">
        {{-- Product summary --}}
        <section class="flex gap-4">
          <div class="flex-shrink-0">
            <img src="{{ $booking['product_image'] ?? $product->thumbnail_url ?? asset('images/placeholder.png') }}" alt="{{ $product->name }}" class="w-28 h-20 object-cover rounded-[10px]" />
          </div>
          <div class="flex-1 space-y-2">
            <h2 class="font-semibold text-base text-slate-900">{{ $product->name ?? 'Product' }}</h2>

            <div class="flex items-center gap-4 text-sm">
              <div class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 fill-green-500 text-green-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                <span class="text-xs text-slate-500"> {{ number_format($booking['rating'] ?? 5, 2) }} </span>
              </div>
              <span class="text-xs text-slate-500"> {{ $booking['trips'] ?? '—' }} Trips </span>
            </div>

            <p class="font-semibold text-base text-slate-900">{{ money($pricePerDay) }} / hari</p>
          </div>
        </section>

        {{-- =========================================== --}}
        {{-- Trip date & time (use booking.start_date if available) --}}
@php
  use Carbon\Carbon;
  $startDateRaw = $booking['start_date'] ?? null;
  $days = isset($booking['days']) ? (int)$booking['days'] : null;

  $startLabel = '—';
  $endLabel = '—';

  if ($startDateRaw) {
      try {
          $start = Carbon::parse($startDateRaw);
          $startLabel = $start->format('j M Y'); // e.g. 12 Dec 2025
          if ($days && $days > 0) {
              $end = (clone $start)->addDays(max(0, $days - 1));
              $endLabel = $end->format('j M Y');
          } else {
              $endLabel = $startLabel;
          }
      } catch (\Exception $e) {
          $startLabel = '—';
          $endLabel = '—';
      }
  }
@endphp

<section class="mt-6 space-y-3">
  <h3 class="font-semibold text-base text-slate-900">Trip Date &amp; Time</h3>
  <div class="bg-blue-50 rounded-xl p-4">
    <p class="font-semibold text-base text-slate-900 text-center">
      {{ $startLabel !== '—' ? ($startLabel . ' — ' . $endLabel) : '—' }}
    </p>
    @if($days)
      <p class="text-xs text-slate-500 text-center mt-1">{{ $days }} {{ $days > 1 ? 'hari' : 'hari' }}</p>
    @endif
  </div>
</section>
        {{-- =========================================== --}}

{{-- Name --}}
<section class="mt-6 space-y-2">
  <label class="font-semibold text-base text-slate-900 block">
    Name
  </label>

  <div
    class="relative rounded-2xl border border-gray-200 bg-white
           transition-all duration-300
           focus-within:border-blue-600
           focus-within:shadow-[0_12px_32px_rgba(0,76,255,0.15)]"
  >
    {{-- Icon --}}
    <div
      class="absolute left-5 top-1/2 -translate-y-1/2
             text-blue-600 transition-transform duration-300
             peer-focus:scale-110"
    >
      @include('icons.person')
    </div>

    <input
      name="full_name"
      type="text"
      placeholder="Write your name"
      value="{{ old('full_name', $booking['full_name'] ?? '') }}"
      required
      class="peer w-full rounded-2xl bg-transparent
             py-4 pl-14 pr-4
             text-base placeholder:text-gray-400
             focus:outline-none"
    />
  </div>
</section>



{{-- Phone --}}
<section class="mt-5 space-y-2">
  <label class="font-semibold text-base text-slate-900 block">
    Phone Number
  </label>

  <div
    class="relative rounded-2xl border border-gray-200 bg-white
           transition-all duration-300
           focus-within:border-blue-600
           focus-within:shadow-[0_12px_32px_rgba(0,76,255,0.15)]"
  >
    {{-- Icon --}}
    <div
      class="absolute left-5 top-1/2 -translate-y-1/2
             text-blue-600 transition-transform duration-300
             peer-focus:scale-110"
    >
      @include('icons.phone')
    </div>

    <input
      name="phone"
      type="tel"
      placeholder="Write your phone number"
      value="{{ old('phone', $booking['phone'] ?? '') }}"
      required
      class="peer w-full rounded-2xl bg-transparent
             py-4 pl-14 pr-4
             text-base placeholder:text-gray-400
             focus:outline-none"
    />
  </div>
</section>



        {{-- Payment details --}}
        <section class="mt-6 space-y-3">
          <h3 class="font-semibold text-base text-slate-900">Payment Details</h3>
          <div class="space-y-3 bg-slate-50 rounded-xl p-4">
            <div class="flex justify-between items-center">
              {{-- <span class="text-xs text-slate-500">{{ money($pricePerDay) }} × {{ $days }} hari</span> --}}
              <span class="text-xs text-slate-500">{{ money($pricePerDay) }} × {{ $unit }} unit × {{ $days }} hari</span>
              <span class="font-medium text-sm text-slate-900">{{ money($subtotal) }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-xs text-slate-500"> PPN 11% </span>
              <span class="font-medium text-sm text-slate-900">{{ money($ppn) }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-xs text-slate-500"> Insurance </span>
              <span class="font-medium text-sm text-slate-900">{{ $insurance ? money($insurance) : '-' }}</span>
            </div>

            <div class="flex justify-between items-center pt-3 border-t border-gray-200">
              <span class="font-medium text-xs text-slate-900">Grand Total</span>
              <span class="font-semibold text-sm text-slate-900">{{ money($grand_total) }}</span>
            </div>
          </div>
        </section>

{{-- Send payment (methods) --}}
<section class="mt-6 space-y-4">
  <h3 class="font-semibold text-base text-slate-900">
    Send Payment
  </h3>

  <div class="space-y-3">
    @forelse($paymentMethods as $pm)
      <label class="block cursor-pointer">
        <input
          type="radio"
          name="payment_method_id"
          value="{{ $pm->id }}"
          class="peer sr-only payment-radio"
          {{ $loop->first ? 'checked' : '' }}
        >

        <div
          class="flex items-center justify-between rounded-2xl border-2
                 px-5 py-4 transition
                 border-gray-200
                 hover:border-gray-300
                 peer-checked:border-blue-600"
        >
          {{-- Left: logo + name --}}
          <div class="flex items-center gap-3">
            <img
              src="{{ $pm->image_url ?? $pm->image ?? asset('images/bank-placeholder.png') }}"
              alt="{{ $pm->name }}"
              class="h-7 w-auto object-contain"
            >
            {{-- <span class="text-base font-semibold text-slate-900">
              {{ $pm->name }}
            </span> --}}
          </div>

          {{-- Right: account --}}
          <span class="text-sm text-slate-500 font-medium">
            @if($pm->account_number)
              {{ $pm->account_number }}
            @else
              -
            @endif
          </span>
        </div>
      </label>
    @empty
      <div class="text-sm text-slate-500">
        Belum ada metode pembayaran. Hubungi admin.
      </div>
    @endforelse
  </div>
</section>



{{-- Confirm Payment --}}
<section class="mt-6 space-y-4">
  <h3 class="font-semibold text-base text-slate-900">
    Confirm Payment
  </h3>

  <div
    class="relative rounded-2xl border border-gray-200 bg-white p-5
           transition-all duration-300
           hover:border-blue-600 hover:shadow-[0_12px_32px_rgba(0,76,255,0.15)]"
  >
    <p class="text-base font-semibold text-slate-900 mb-3">
      Upload Proof
    </p>

    {{-- Upload area --}}
    <label
      class="relative flex items-center gap-4 cursor-pointer
             rounded-xl border border-dashed border-gray-300
             bg-gray-50 px-4 py-4
             transition-all duration-300
             hover:bg-blue-50 hover:border-blue-600"
    >
      {{-- Icon --}}
      <div
        class="flex h-12 w-12 items-center justify-center
               rounded-xl  text-white flex-shrink-0"
      >
        @include('icons.image')
      </div>

      {{-- Text --}}
      <div class="flex flex-col leading-tight">
        <span class="text-sm font-semibold text-slate-900">
          Add an attachment
        </span>
        <span class="text-xs text-slate-500">
          Upload payment proof (JPG / PNG)
        </span>
      </div>

      {{-- Real input (tetap native & required) --}}
      <input
        type="file"
        name="payment_proof"
        accept="image/*"
        required
        id="payment-proof-input"
        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
      >
    </label>

    {{-- Preview filename --}}
    <div
      id="file-preview"
      class="mt-3 text-xs text-slate-600"
    ></div>
  </div>
</section>


        {{-- bottom CTA (fixed) --}}
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
      {{-- Total --}}
      <div class="flex flex-col leading-tight">
        <span class="text-xs text-gray-500 font-medium">
          Total
        </span>
        <span class="text-lg font-bold text-gray-900">
          {{ money($grand_total) }}
        </span>
      </div>

      {{-- CTA --}}
      <button
        type="submit"
        class="bg-blue-600 text-white font-bold py-2.5 px-8 rounded-xl
               hover:bg-blue-700 active:scale-95 transition text-sm"
      >
        Check out
      </button>
    </div>

  </div>
</div>

      </form>
    </main>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function(){
    // highlight selected payment card
    document.querySelectorAll('.payment-radio').forEach(function(radio){
      const wrapper = radio.closest('label').querySelector('div');
      radio.addEventListener('change', function(){
        document.querySelectorAll('label > div').forEach(d => d.classList.remove('border-blue-600','bg-blue-50'));
        if(radio.checked){
          wrapper.classList.add('border-blue-600','bg-blue-50');
        }
      });
      // init style if checked by default
      if(radio.checked){
        wrapper.classList.add('border-blue-600','bg-blue-50');
      }
    });

    // file input preview
    const fileInput = document.getElementById('payment-proof-input');
    const preview = document.getElementById('file-preview');
    if(fileInput){
      fileInput.addEventListener('change', function(e){
        const f = e.target.files[0];
        if(!f){ preview.textContent = ''; return; }
        preview.textContent = f.name + ' (' + Math.round(f.size/1024) + ' KB)';
      });
    }


  });
</script>
@endpush
