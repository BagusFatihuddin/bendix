@extends('front.layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-100">
  <div class="bg-white w-full max-w-[375px] min-h-screen px-5 py-6">

    {{-- TOP BAR --}}
    <div class="mb-8 flex items-center justify-between">
      <a href="{{ url()->previous() }}"
         class="inline-flex items-center gap-2 rounded-full border border-[#DFDEDE] p-2">
        <svg width="24" height="24" fill="none" stroke="#004CFF" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <path d="m15 18-6-6 6-6"></path>
        </svg>
      </a>

      <h1 class="text-lg font-semibold text-[#131313]">
        Booking Details
      </h1>

      <span class="w-10"></span>
    </div>

    {{-- BOOKING ID --}}
    <div class="mb-6 flex items-start gap-4">
      <div class="border-2 border-[#131313] px-2 py-1 text-xs font-bold">
        ID
      </div>
      <div>
        <p class="text-base font-semibold text-[#131313]">
          {{ $tx->trx_id }}
        </p>
        <p class="text-xs text-[#777]">
          Your Booking ID
        </p>
      </div>
    </div>

    {{-- STATUS --}}
    <div class="mb-8 space-y-4">

      @if($tx->status === 'payment_review')
        <div class="flex gap-4 rounded-2xl border p-4">
          <div>
            <p class="text-sm font-bold text-[#131313]">
              Payment Pending
            </p>
            <p class="text-xs text-gray-500">
              Tim kami sedang memeriksa pembayaran Anda
            </p>
          </div>
        </div>
      @endif

      @if($tx->status === 'paid')
        <div class="flex gap-4 rounded-2xl border p-4">
          <div>
            <p class="text-sm font-bold text-[#131313]">
              Payment Success
            </p>
            <p class="text-xs text-gray-500">
              Pembayaran berhasil, silakan menunggu instruksi selanjutnya
            </p>
          </div>
        </div>
      @endif

    </div>

    {{-- PRODUCT CARD --}}
    <div class="mb-8 flex gap-4">
      <div class="w-28 h-20 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center">
        <img
          src="{{ $tx->product->thumbnail_url }}"
          alt="{{ $tx->product->name }}"
          class="w-full h-full object-contain"
        />
      </div>

      <div class="flex-1">
        <p class="font-semibold text-[#131313]">
          {{ $tx->product->name }}
        </p>
        <p class="text-sm text-gray-500">
          Rp {{ number_format($tx->total_amount, 0, ',', '.') }} / hari
        </p>
      </div>
    </div>

    {{-- DETAIL INFO --}}
    <div class="space-y-4 mb-8 text-sm text-[#131313]">

      <div>
        <span class="font-semibold">Name</span>
        <p>{{ $tx->name }}</p>
      </div>

      <div>
        <span class="font-semibold">Phone Number</span>
        <p>{{ $tx->phone_number }}</p>
      </div>

      <div>
        <span class="font-semibold">Started At</span>
        <p>{{ optional($tx->started_at)->format('d M Y') }}</p>
      </div>

      <div>
        <span class="font-semibold">Ended At</span>
        <p>{{ optional($tx->ended_at)->format('d M Y') }}</p>
      </div>

      @if($tx->delivery_type === 'pickup')
        <div>
          <span class="font-semibold">Pick up at</span>
          <p>{{ $tx->store->name ?? '-' }}</p>
        </div>
      @else
        <div>
          <span class="font-semibold">Home Delivery</span>
          <p>{{ $tx->address }}</p>
        </div>
      @endif

    </div>

    {{-- PAYMENT DETAILS --}}
    <div>
      <h2 class="mb-4 text-base font-semibold text-[#131313]">
        Payment Details
      </h2>

      <div class="space-y-3 text-sm">
        <div class="flex justify-between">
          <span>Subtotal</span>
          <span>Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
</span>
        </div>

        <div class="border-t pt-3 flex justify-between font-semibold">
          <span>Grand Total</span>
          <span>Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
</span>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
