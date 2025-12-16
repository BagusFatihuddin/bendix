@extends('front.layouts.app')

@section('title', 'Booking - ' . $product->name)

@section('content')
{{-- @php
    dd([
        'booking_before_render' => session("booking.$product->id"),
        'all' => session()->all(),
    ]);
@endphp --}}


<div>
<div class="sticky top-0 z-10 bg-white border-b border-[#DFDEDE] px-4 py-3">
  <div class="grid grid-cols-3 items-center">

    {{-- LEFT: Back button --}}
    <div class="flex justify-start">
      <button
        onclick="history.back()"
        aria-label="Kembali"
        class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md hover:opacity-80"
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
    </div>

    {{-- CENTER: Title --}}
    <h1 class="text-base font-bold text-[#121111] text-center">
      Booking
    </h1>

    {{-- RIGHT: List icon --}}
    <div class="flex justify-end">
      <button
        aria-label="List"
        class="flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-md hover:opacity-80 text-blue-600"
      >
        @include('icons.list')
      </button>
    </div>

  </div>
</div>


  <form action="{{ route('front.booking_save', $product->slug) }}" method="POST" id="booking-form">
    @csrf

    <div class="px-4 py-5 space-y-5 pb-32">
      <!-- PRODUCT SUMMARY -->
      <div class="flex gap-4 items-center">
        <div class="flex-shrink-0">
          <div class="w-30 h-20 rounded-xl overflow-hidden bg-gray-100">
            <img src="{{ $product->thumbnail_url ?? asset('images/placeholder.png') }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
          </div>
        </div>

        <div class="flex-1 flex flex-col justify-center gap-2">
          <div class="space-y-0.5">
            <h2 class="text-sm font-semibold text-[#121111] leading-tight">{{ $product->name }}</h2>
            <p class="text-xs text-[#787676]">{{ $product->category->name ?? '' }}</p>
          </div>
          <p class="text-sm font-semibold text-[#292526]">Rp {{ number_format($product->price,0,',','.') }}</p>
        </div>

        <!-- UNIT CONTROLLER -->
<div class="flex flex-col items-end gap-3 flex-shrink-0">
  <div class="flex items-center gap-1">
    <button type="button" class="unit-btn w-6 h-6 border rounded-full flex items-center justify-center" data-type="dec">-</button>
    <span class="text-sm font-semibold w-8 text-center" id="unit-display">{{ $old['unit'] ?? 1 }}</span>
    <button type="button" class="unit-btn w-6 h-6 border rounded-full flex items-center justify-center" data-type="inc">+</button>
  </div>
</div>
      </div>

                <!-- How many days (separate section for better UX) -->
<!-- How many days -->
<div class="flex items-center justify-between py-3">
  <!-- Left: Label -->
  <h3 class="text-lg font-bold text-[#1A1A1A]">
    How many days?
  </h3>

  <!-- Right: Controls -->
  <div class="flex items-center gap-4">
    <!-- Minus -->
    <button
      type="button"
      data-type="dec"
      class="days-btn flex items-center justify-center w-7 h-7 rounded-full
             border-2 border-black text-black font-bold
             hover:bg-blue-50 active:scale-95 transition"
    >
      <span class="text-xl leading-none">−</span>
    </button>

    <!-- Value -->
    <span
      id="days-display-2"
      class="text-xl font-semibold text-[#1A1A1A] w-6 text-center"
    >
      {{ $old['days'] ?? 1 }}
    </span>

    <!-- Plus -->
    <button
      type="button"
      data-type="inc"
      class="days-btn flex items-center justify-center w-7 h-7 rounded-full
             border-2 border-black text-black font-bold
             hover:bg-blue-50 active:scale-95 transition"
    >
      <span class="text-xl leading-none">+</span>
    </button>
  </div>
</div>



      <!-- STARTED AT -->
<div class="space-y-2">
  <h4 class="text-base font-semibold text-gray-900">
    Started At
  </h4>

  <label class="relative block cursor-pointer">
    {{-- Icon kiri --}}
    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600">
      @include('icons.date')
    </div>

    {{-- CTA kanan --}}
    <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 text-blue-600 font-semibold text-sm">
      <span>Add Dates</span>
      @include('icons.next-btn')
    </div>

    <input
      type="text"
      id="start-date"
      name="start_date"
      value="{{ $old['start_date'] ?? '' }}"
      readonly
      required
      class="date-input-clean w-full rounded-2xl border
             border-[rgba(19,19,19,0.10)]
             bg-white py-4 pl-12 pr-24 text-sm text-gray-700
             focus:outline-none focus:ring-2 focus:ring-blue-600/20"
    />
  </label>
</div>



<!-- DELIVERY TOGGLE -->
<div class="space-y-2">
  <h4 class="text-base font-semibold text-gray-900">
    Delivery
  </h4>

  <div
    class="relative flex items-center rounded-full bg-gray-100 p-1"
    role="tablist"
  >
    {{-- Home delivery --}}
    <button
      type="button"
      data-value="home_delivery"
      class="delivery-btn relative z-10 flex-1 rounded-full py-3 text-sm font-semibold transition-all duration-300
      {{ (($old['delivery_method'] ?? 'home_delivery') === 'home_delivery')
          ? 'bg-white text-blue-600 shadow-[0_8px_24px_rgba(0,0,0,0.08)]'
          : 'text-gray-500 hover:text-gray-700' }}"
    >
      Home delivery
    </button>

    {{-- Pick up --}}
    <button
      type="button"
      data-value="pickup"
      class="delivery-btn relative z-10 flex-1 rounded-full py-3 text-sm font-semibold transition-all duration-300
      {{ (($old['delivery_method'] ?? 'home_delivery') === 'pickup')
          ? 'bg-white text-blue-600 shadow-[0_8px_24px_rgba(0,0,0,0.08)]'
          : 'text-gray-500 hover:text-gray-700' }}"
    >
      Pick up in store
    </button>
  </div>
</div>




      <!-- PICKUP LOCATIONS -->
<!-- PICKUP LOCATIONS -->
<div id="pickup-section" class="space-y-3">
  <h4 class="text-base font-semibold text-gray-900">
    Choose pick up location
  </h4>

  @foreach($stores as $s)
    <label class="block w-full cursor-pointer">
      <input
        type="radio"
        name="store_id"
        value="{{ $s->id }}"
        class="peer sr-only store-radio"
        {{ (isset($old['store_id']) && $old['store_id'] == $s->id) ? 'checked' : '' }}
      >

      <div
        class="flex items-center gap-3 rounded-2xl border border-[rgba(19,19,19,0.10)]
               bg-white px-4 py-3 transition
               hover:border-blue-600
               peer-checked:border-blue-600
               peer-checked:ring-2 peer-checked:ring-blue-200"
      >
        {{-- Store icon --}}
        <div class="flex h-10 w-10 items-center justify-center rounded-xl
                    bg-blue-50 text-blue-600 flex-shrink-0">
          @include('icons.store')
        </div>

        {{-- Store info --}}
        <div class="flex flex-col leading-tight">
          <span class="text-sm font-semibold text-gray-900">
            {{ $s->name }}
          </span>
          <span class="text-xs text-gray-500">
            {{ $s->address ?? '' }}
          </span>
        </div>
      </div>
    </label>
  @endforeach
</div>


<!-- DELIVERY ADDRESS -->
<div id="delivery-section" class="space-y-6 hidden">

  {{-- Address --}}
  <div class="space-y-2">
    <h4 class="text-base font-semibold text-gray-900">
      Address
    </h4>

    <div
      class="relative rounded-2xl border border-[rgba(19,19,19,0.10)] bg-white
             transition-all duration-300
             focus-within:border-blue-600
             focus-within:shadow-[0_12px_32px_rgba(0,76,255,0.15)]"
    >
      {{-- Icon --}}
      <div
        class="absolute left-4 top-4 text-blue-600
               transition-transform duration-300
               peer-focus:scale-110"
      >
        @include('icons.location')
      </div>

      <textarea
        name="address"
        rows="2"
        placeholder="Alamat lengkap (jalan, nomor, kec./kota)"
        class="peer w-full resize-none rounded-2xl bg-transparent
               pl-12 pr-4 py-4 text-base
               placeholder:text-gray-400
               focus:outline-none"
      >{{ $old['address'] ?? '' }}</textarea>
    </div>
  </div>

  {{-- Details --}}
  <div class="space-y-2">
    <h4 class="text-base font-semibold text-gray-900">
      Details
    </h4>

    <div
      class="relative rounded-2xl border border-[rgba(19,19,19,0.10)] bg-white
             transition-all duration-300
             focus-within:border-blue-600
             focus-within:shadow-[0_12px_32px_rgba(0,76,255,0.15)]"
    >
      {{-- Icon --}}
      <div
        class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600
               transition-transform duration-300
               peer-focus:scale-110"
      >
        @include('icons.detail_location')
      </div>

      <input
        type="text"
        name="address_detail"
        placeholder="Detail alamat (opsional, contoh: dekat pohon beringin)"
        value="{{ $old['address_detail'] ?? '' }}"
        class="peer w-full rounded-2xl bg-transparent
               pl-12 pr-4 py-4 text-base
               placeholder:text-gray-400
               focus:outline-none"
      />
    </div>
  </div>

</div>
<!-- DELIVERY ADDRESS -->




<!-- HIDDEN INPUTS -->
<input type="hidden" name="days" id="days-input" value="{{ $old['days'] ?? 1 }}">
<input type="hidden" name="unit" id="unit-input" value="{{ $old['unit'] ?? 1 }}">
<input type="hidden" name="delivery_method" id="delivery-input"
       value="{{ $old['delivery_method'] ?? 'home_delivery' }}" />
{{-- <input type="hidden" name="delivery_method" id="delivery-input" value="{{ $old['delivery_method'] ?? 'pickup' }}" /> --}}

</div>

    <!-- fixed bottom checkout bar -->
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
      {{-- Total Price --}}
      <div class="flex flex-col leading-tight">
        <span class="text-xs text-gray-500 font-medium">
          Total
        </span>
        <span class="text-lg font-bold text-gray-900" id="total-display">
          Rp {{ number_format($product->price * ($old['unit'] ?? 1) * ($old['days'] ?? 1),0,',','.') }}
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
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
  // reload jika halaman datang dari bfcache (back/forward)
  window.addEventListener("pageshow", function (event) {
    if (event.persisted || (performance.getEntriesByType && performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
      window.location.reload();
    }
  });

      flatpickr("#start-date", {
      dateFormat: "Y-m-d",
      allowInput: false,
      clickOpens: true,
      disableMobile: true,
    });

  const form = document.getElementById('booking-form');
  if (!form) return;

  // elements
  const submitBtn = form.querySelector('button[type="submit"]');
  const deliveryInput = document.getElementById('delivery-input');
  const pickupSection = document.getElementById('pickup-section');
  const deliverySection = document.getElementById('delivery-section');
  const deliveryBtns = Array.from(document.querySelectorAll('.delivery-btn'));

  // controls for unit/days
  const unitInput = document.getElementById('unit-input');
  const daysInput = document.getElementById('days-input');
  const unitDisplay = document.getElementById('unit-display');
  const daysDisplay2 = document.getElementById('days-display-2');
  const unitBtns = Array.from(document.querySelectorAll('.unit-btn'));
  const daysBtns = Array.from(document.querySelectorAll('.days-btn'));
  const totalDisplay = document.getElementById('total-display');

  // price from blade (integer)
  const pricePerDay = {{ (int) $product->price }};
  const MAX_UNITS = 50;
  const MAX_DAYS = 365;

  // helper to read radios
  function getStoreRadios(){ return Array.from(document.querySelectorAll('.store-radio')); }
  function isStoreSelected(){ return getStoreRadios().some(r => r.checked); }

  // visual helpers for store cards
  function clearAllCardVisuals(){
    document.querySelectorAll('#pickup-section .store-card').forEach(card => card.classList.remove('ring-2','ring-blue-200'));
  }
  function markSelectedCard(){
    clearAllCardVisuals();
    const checked = getStoreRadios().find(r => r.checked);
    if(checked){
      const card = checked.closest('label')?.querySelector('.store-card');
      if(card) card.classList.add('ring-2','ring-blue-200');
    }
  }

  // read initial state (fallbacks)
  let unit = parseInt(unitInput?.value) || 1;
  let days = parseInt(daysInput?.value) || 1;

  function clamp(v, min, max){ return Math.max(min, Math.min(max, v)); }

  // main update function: updates displays, hidden inputs, total
  function updateBookingUI(){
    if(unitInput) unitInput.value = unit;
    if(daysInput) daysInput.value = days;
    if(unitDisplay) unitDisplay.textContent = unit;
    if(daysDisplay2) daysDisplay2.textContent = days;
    const subtotal = pricePerDay * unit * days;
    if(totalDisplay) totalDisplay.textContent = 'Rp ' + subtotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    document.dispatchEvent(new CustomEvent('bookingChanged', { detail: { unit, days, subtotal } }));
  }

  // is home delivery fields valid?
function isHomeDeliveryValid(){
  const addressEl = form.querySelector("textarea[name='address']");
  if(!addressEl) return false;
  return addressEl.value.trim().length > 0;
}


  // show/hide helpers
  function showPickup(){ pickupSection.classList.remove('hidden'); deliverySection.classList.add('hidden'); }
  function showDelivery(){ pickupSection.classList.add('hidden'); deliverySection.classList.remove('hidden'); }

  //cek date or started at
  function isStartDateValid(){
  const el = form.querySelector("input[name='start_date']");
  return el && el.value;
}


  // update submit button enabled/disabled depending on delivery & fields
  function updateSubmitState(){

      if(!isStartDateValid()){
        if(submitBtn){
          submitBtn.disabled = true;
          submitBtn.classList.add('opacity-60','cursor-not-allowed');
        }
        return;
      }

    const delivery = (deliveryInput && deliveryInput.value) ? deliveryInput.value : 'pickup';

    // default disabled
    if(submitBtn){
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-60','cursor-not-allowed');
    }

    if(delivery === 'pickup'){
      if(!isStoreSelected()){
        if(!document.querySelector('#choose-store-hint') && pickupSection){
          const hint = document.createElement('p');
          hint.id = 'choose-store-hint';
          hint.className = 'text-xs text-red-600 mt-2';
          hint.innerText = 'Pilih lokasi pengambilan sebelum lanjut ke checkout.';
          pickupSection.prepend(hint);
        }
        return; // remain disabled
      } else {
        const h = document.querySelector('#choose-store-hint'); if(h) h.remove();
        if(submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60','cursor-not-allowed'); }
        return;
      }
    } else {
      // home_delivery: require full_name, phone, address non-empty
      const h = document.querySelector('#choose-store-hint'); if(h) h.remove();
      if(isHomeDeliveryValid()){
        if(submitBtn){ submitBtn.disabled = false; submitBtn.classList.remove('opacity-60','cursor-not-allowed'); }
      } else {
        if(submitBtn){ submitBtn.disabled = true; submitBtn.classList.add('opacity-60','cursor-not-allowed'); }
      }
    }
  }

  // attach handlers to unit buttons
  unitBtns.forEach(btn => {
    btn.addEventListener('click', function(){
      const type = this.getAttribute('data-type');
      if(type === 'inc') unit = clamp(unit + 1, 1, MAX_UNITS);
      if(type === 'dec') unit = clamp(unit - 1, 1, MAX_UNITS);
      updateBookingUI();
      updateSubmitState();
    });
  });

  // attach handlers to days buttons
  daysBtns.forEach(btn => {
    btn.addEventListener('click', function(){
      const type = this.getAttribute('data-type');
      if(type === 'inc') days = clamp(days + 1, 1, MAX_DAYS);
      if(type === 'dec') days = clamp(days - 1, 1, MAX_DAYS);
      updateBookingUI();
      updateSubmitState();
    });
  });

  // pickup card click selects store radio
  if(pickupSection){
    pickupSection.addEventListener('click', function(e){
      const label = e.target.closest('label');
      if(!label || !pickupSection.contains(label)) return;
      const radio = label.querySelector('.store-radio');
      if(radio){
        radio.checked = true;
        markSelectedCard();
        updateSubmitState();
      }
    });
  }

  // keyboard accessibility for radios + change listener for radios
  document.addEventListener('change', function(e){
    if(e.target && e.target.classList && e.target.classList.contains('store-radio')){
      markSelectedCard();
      updateSubmitState();
    }
  });

  // delivery button toggles
deliveryBtns.forEach(btn => {
  btn.addEventListener('click', function () {

    // reset semua button
    deliveryBtns.forEach(b => {
      b.classList.remove(
        'bg-white',
        'text-blue-600',
        'font-semibold',
        'shadow-[0_8px_24px_rgba(0,0,0,0.08)]'
      );
      b.classList.add('text-gray-500');
    });

    // aktifkan button yang diklik
    this.classList.add(
      'bg-white',
      'text-blue-600',
      'font-semibold',
      'shadow-[0_8px_24px_rgba(0,0,0,0.08)]'
    );
    this.classList.remove('text-gray-500');

    const val = this.getAttribute('data-value');
    if (deliveryInput) deliveryInput.value = val;

    if (val === 'pickup') {
      showPickup();
    } else {
      showDelivery();
    }

    // pastikan state submit ikut update
    setTimeout(updateSubmitState, 20);
  });
});


  // attach handlers to full_name / phone / address to update submit state live
const addressEl = form.querySelector("textarea[name='address']");
const addressDetailEl = form.querySelector("input[name='address_detail']");

[addressEl, addressDetailEl].forEach(el => {
  if(el){
    el.addEventListener('input', function(){
      updateSubmitState();
    });
  }
});

// started_at (start_date) wajib dipilih
const startDateEl = form.querySelector("input[name='start_date']");
if(startDateEl){
  startDateEl.addEventListener('change', updateSubmitState);
}


  // init when page load
  (function init(){
    // const current = (deliveryInput && deliveryInput.value) ? deliveryInput.value : 'pickup';
    const current = (deliveryInput && deliveryInput.value)
  ? deliveryInput.value
  : 'home_delivery';

    deliveryBtns.forEach(b => {
      if(b.getAttribute('data-value') === current){
        b.classList.add('bg-white','text-blue-600','shadow-sm','font-semibold');
      } else {
        b.classList.remove('bg-white','text-blue-600','shadow-sm','font-semibold');
      }
    });
    if(current === 'pickup') showPickup(); else showDelivery();
    markSelectedCard();
    updateBookingUI();
    // call updateSubmitState a bit later to let DOM settle
    setTimeout(updateSubmitState, 30);
  })();

  // final pre-submit validation (extra guard)
  form.addEventListener('submit', function(ev){
    const val = (deliveryInput && deliveryInput.value) ? deliveryInput.value : 'pickup';
    if(val === 'home_delivery'){
      if(!isHomeDeliveryValid()){
        alert('Untuk pengiriman ke alamat, lengkapi nama, nomor telepon, dan alamat pengiriman.');
        ev.preventDefault();
        return;
      }
    } else {
      if(!isStoreSelected()){
        alert('Pilih lokasi pick up terlebih dahulu.');
        ev.preventDefault();
        return;
      }
    }
    // disable to avoid double submit
    if(submitBtn){
      submitBtn.disabled = true;
      submitBtn.classList.add('opacity-60','cursor-not-allowed');
    }
  });

});
</script>

