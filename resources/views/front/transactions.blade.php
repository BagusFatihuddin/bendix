@extends('front.layouts.app')

@section('title', 'My Booking')

@section('content')
<div class="min-h-screen bg-white relative overflow-hidden">

  {{-- Background Image --}}
  <div class="absolute inset-0 pointer-events-none">
    <img
      src="https://res.cloudinary.com/dshhlawvf/image/upload/v1765887326/bg-cek_sioag3.png"
      alt=""
      class="absolute top-0 left-0 w-full object-cover"
    >
  </div>

  {{-- Container --}}
  <div class="relative flex justify-center px-5">
    <div class="w-full max-w-[375px] pt-16 pb-12">

      {{-- Logo --}}
      <div class="flex flex-col items-center mb-8">
        <div class="w-[150px] h-[150px] mb-4">
          @include('icons.bendix')
        </div>

        <h1 class="font-raleway text-[48px] font-bold tracking-[-0.52px] text-[#202020]">
          Bendix
        </h1>
      </div>

      {{-- Error --}}
      @if($errors->any())
        <div class="mb-4 rounded-xl bg-red-50 p-3 text-sm text-red-600">
          {{ $errors->first() }}
        </div>
      @endif

      {{-- FORM --}}
      <form
        action="{{ route('front.transaction.details') }}"
        method="POST"
        class="space-y-[17px] px-5"
      >
        @csrf

        {{-- Phone --}}
        <div class="flex flex-col gap-2">
          <label class="font-inter text-[15px] font-semibold text-[#131313]">
            Phone Number
          </label>

          <div class="w-full h-[75px] rounded-[14px] border border-gray-200 flex items-center px-4">
            <div class="flex items-center gap-[10px] w-full">
              @include('icons.phone')

              <input
                type="tel"
                name="phone"
                required
                placeholder="Write your phone number"
                class="flex-1 bg-transparent border-none outline-none
                       font-inter text-[12px] text-gray-400
                       placeholder:text-gray-400"
              >
            </div>
          </div>
        </div>

        {{-- Booking ID --}}
        <div class="flex flex-col gap-2">
          <label class="font-inter text-[15px] font-semibold text-[#131313]">
            Your Booking ID
          </label>

          <div class="w-full h-[75px] rounded-[14px] border border-gray-200 flex items-center px-4">
            <div class="flex items-center gap-[10px] w-full">
              @include('icons.id')

              <input
                type="text"
                name="transaction_code"
                required
                placeholder="Write your Booking ID"
                class="flex-1 bg-transparent border-none outline-none
                       font-inter text-[12px] text-gray-400
                       placeholder:text-gray-400"
              >
            </div>
          </div>
        </div>

        {{-- CTA --}}
        <button
          type="submit"
          class="w-full h-[51px] mt-8 rounded-full
                 bg-[#004BFE] hover:bg-blue-600
                 transition font-encode-sans text-[18px]
                 font-semibold text-white"
        >
          Check My Booking
        </button>

      </form>

    </div>
  </div>
</div>
@endsection
