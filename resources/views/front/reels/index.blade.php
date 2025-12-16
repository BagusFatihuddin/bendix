@extends('front.layouts.app')

@section('title', 'Reels')

@section('content')
<div class="bg-black min-h-screen overflow-y-scroll snap-y snap-mandatory">

  <div class="max-w-[420px] mx-auto relative">

    @foreach ($reels as $reel)
      <section
        class="reel-item snap-start h-screen w-full relative overflow-hidden"
      >

        {{-- VIDEO --}}
        <video
          class="reel-video absolute inset-0 w-full h-full object-cover"
          muted
          loop
          playsinline
          preload="metadata"
        >
          <source
            src="{{ $reel->public_id }}"
            type="video/mp4"
          >
        </video>

        {{-- GRADIENT OVERLAY --}}
        <div class="absolute inset-0 pointer-events-none">
          <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
          <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-black/40 to-transparent"></div>
        </div>

        {{-- SOUND BUTTON --}}
        <button
          class="absolute top-4 right-4 z-20
                 bg-black/60 backdrop-blur
                 text-white text-xs
                 px-3 py-1.5 rounded-full
                 hover:bg-black/80 transition"
          onclick="toggleSound(event, this)"
        >
          🔇
        </button>

        {{-- RIGHT ACTIONS --}}
        <div class="absolute right-3 bottom-24 z-20 flex flex-col items-center gap-4 text-white">
          <button class="flex flex-col items-center gap-1 opacity-90 hover:opacity-100">
            ❤️
            <span class="text-[11px]">1.2k</span>
          </button>
          <button class="flex flex-col items-center gap-1 opacity-90 hover:opacity-100">
            💬
            <span class="text-[11px]">98</span>
          </button>
          <button class="flex flex-col items-center gap-1 opacity-90 hover:opacity-100">
            ↗️
            <span class="text-[11px]">Share</span>
          </button>
        </div>

        {{-- BOTTOM INFO --}}
        <div class="absolute bottom-6 left-4 right-20 z-20 text-white">
          <div class="font-semibold text-sm">@reels_user</div>
          <p class="text-xs opacity-90 leading-snug mt-1 line-clamp-2">
            Ini contoh caption reels. Bisa panjang, bisa pendek, tetap rapi.
          </p>
        </div>

      </section>
    @endforeach

  </div>

</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const videos = document.querySelectorAll('.reel-video')

  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        const video = entry.target

        if (entry.isIntersecting) {
          video.play()
        } else {
          video.pause()
          video.muted = true
          const btn = video.closest('.reel-item').querySelector('button')
          if (btn) btn.innerText = '🔇'
        }
      })
    },
    { threshold: 0.6 }
  )

  videos.forEach(video => observer.observe(video))
})

function toggleSound(e, btn) {
  e.stopPropagation()
  const video = btn.closest('.reel-item').querySelector('video')

  video.muted = !video.muted
  btn.innerText = video.muted ? '🔇' : '🔊'
}
</script>
@endpush
