@props(['categories'])

<div class="px-4 mb-8">
  <div
    class="flex flex-wrap justify-center gap-x-5 gap-y-4"
    aria-label="Kategori"
  >
    @foreach($categories as $cat)
      <a
        href="{{ route('front.category', $cat->slug) }}"
        class="flex flex-col items-center gap-2 w-[72px]"
      >
        <div
          class="w-[60px] h-[60px] bg-white rounded-full shadow-md
                 flex items-center justify-center overflow-hidden"
        >
          @if(!empty($cat->icon_url))
            <img
              src="{{ $cat->icon_url }}"
              alt="{{ $cat->name }}"
              class="w-7 h-7 object-contain"
              loading="lazy"
            />
          @else
            <span class="text-sm font-semibold text-primary">
              {{ strtoupper(substr($cat->name,0,1)) }}
            </span>
          @endif
        </div>

        <p class="text-[13px] font-medium text-gray-900 text-center truncate">
          {{ $cat->name }}
        </p>
      </a>
    @endforeach
  </div>
</div>
