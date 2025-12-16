{{-- resources/views/front/success_booking.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Finish Booking</title>

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Fonts --}}
  <link
    href="https://fonts.googleapis.com/css2?family=Raleway:wght@600;700&family=Nunito:wght@400;600&display=swap"
    rel="stylesheet"
  />

  <style>
    body { font-family: 'Nunito', sans-serif; }
    .font-raleway { font-family: 'Raleway', sans-serif; }
  </style>
</head>

<body class="mx-auto max-w-[375px] bg-[#F2F5FE]">

  <main
    class="min-h-screen bg-no-repeat bg-top bg-contain px-5 flex items-center"
    style="background-image: url('https://res.cloudinary.com/dshhlawvf/image/upload/v1765792480/Bubbles-finihbro_vvlxhj.png');"
  >
    <section class="w-full flex flex-col items-center text-center">

      {{-- Title --}}
      <h1 class="font-raleway text-[30px] leading-[40px] font-bold text-[#202020] mb-3">
        Finish Booking
      </h1>

      <p class="text-[15px] leading-[24px] text-[#202020]/70 max-w-[260px] mb-6">
        Booking kamu berhasil. Tim kami sedang memeriksa pembayaran kamu.
      </p>

      {{-- Product Image --}}
      <div class="w-[150px] h-[225px] rounded-2xl overflow-hidden shadow-md bg-white mb-6">
        <img
          src="{{ $transaction->product->thumbnail_url ?? asset('images/placeholder.png') }}"
          alt="{{ $transaction->product->name ?? 'Product' }}"
          class="w-full h-full object-cover"
        />
      </div>

      {{-- Booking ID Card --}}
      <div class="w-full mb-8">
        <div class="flex items-center gap-4 bg-white rounded-2xl px-4 py-4 shadow-sm border border-[#E9EDFF]">

          {{-- Icon --}}
          <div class="flex items-center justify-center w-10 h-10 rounded-full bg-[#EEF2FF] shrink-0">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd"
                d="M12.0092 15.003L12.0152 13C12.0152 12.7341 12.1209 12.4791 12.3089 12.2911C12.4969 12.1031 12.7519 11.9975 13.0177 11.9975C13.2836 11.9975 13.5386 12.1031 13.7266 12.2911C13.9146 12.4791 14.0202 12.7341 14.0202 13V14.977C14.0202 15.458 14.0202 15.699 14.1742 15.847C14.3292 15.994 14.5642 15.984 15.0372 15.964C16.9002 15.885 18.0452 15.634 18.8512 14.828C19.6612 14.022 19.9122 12.877 19.9912 11.011Z"
                fill="#004CFF"/>
            </svg>
          </div>

          {{-- Text --}}
          <div class="text-left">
            <p class="text-[14px] text-[#202020]/60">
              Booking ID
            </p>
            <p class="text-[16px] font-semibold text-[#202020] tracking-wide">
              {{ $transaction->trx_id }}
            </p>
          </div>
        </div>
      </div>

      {{-- CTA --}}
      <div class="w-full space-y-4">
        <a
          href="{{ route('front.index') }}"
          class="block w-full h-[50px] rounded-full bg-[#004BFE] text-white text-[16px] font-semibold flex items-center justify-center transition active:scale-[0.97]"
        >
          Rent More
        </a>

        <a
          href="{{ route('front.transaction.detail', $transaction->id) }}"
          class="block w-full h-[50px] rounded-full border-2 border-[#004BFE] text-[#004BFE] text-[16px] font-semibold flex items-center justify-center transition active:scale-[0.97]"
        >
          My Booking
        </a>
      </div>

    </section>
  </main>

</body>
</html>
