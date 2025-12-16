<?php

namespace App\Http\Controllers;
use App\Models\Store; // kalau model Store ada
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\PaymentMethod;


class FrontController extends Controller
{
//⁡⁣⁣⁡⁣⁣⁢===============================================================⁡⁡⁡ 
public function index()
{
    $products = Product::with(['brand','category'])
                ->orderBy('created_at','desc')
                ->paginate(8);

    $categories = Category::orderBy('name')->get();

    // ads statis: kita buat collection manual (bisa diganti ke DB nanti)
    $ads = collect([
        (object)[
            'title' => 'Big Sale',
            'subtitle' => 'Up to 50%',
            'image_url' => asset('images/hero-sample.jpg'),
            'cta_text' => 'Shop Now',
            'cta_url' => route('front.index'),
        ],
    ]);

    return view('front.index', compact('products','categories','ads'));
}

//⁡⁣⁣⁢===============================================================⁡⁡ 
public function details(Product $product)
{
    // Eager load relations if needed
    $product->load(['brand', 'category', 'photos']);

    // If you use an accessor like image_url, it's fine; otherwise use thumbnail_url
    return view('front.details', compact('product'));
}


//⁡⁣⁣⁢===============================================================⁡ 
    public function booking(Product $product)
    {
        $stores = Store::all();
        // ambil old booking dari session (jika ada) — kunci konsisten: booking.{product_id}
        $old = session("booking.{$product->id}") ?? [];
        return view('front.booking', compact('product','stores','old'));
    }

//⁡⁣⁣⁢===============================================================⁡⁡ 

 public function booking_save(Request $request, Product $product)
    {
// validasi minimal (sesuaikan)
$data = $request->validate([
    'unit' => 'nullable|integer|min:1',
    'days' => 'nullable|integer|min:1',
    // 'start_date' => 'nullable|date',\
    'start_date' => 'required|date',
    'delivery_method' => 'nullable|string',
    'store_id' => 'nullable|integer|exists:stores,id',
    // catatan: nama & phone dihapus dari booking; akan diisi di checkout
    'address' => 'nullable|string',
    'address_detail' => 'nullable|string',
]);

        // cast aman & default
$unit = isset($data['unit']) ? (int) $data['unit'] : 1;
$days = isset($data['days']) ? (int) $data['days'] : 1;

        $startDate = $data['start_date'] ?? null;
        $endDate = null;
        if ($startDate) {
            $add = max(0, $days - 1);
            $endDate = Carbon::parse($startDate)->addDays((int)$add)->format('Y-m-d');
        }

        // harga per hari dari product
        $pricePerDay = (int) $product->price;
        $subtotal = $pricePerDay * $unit * $days;
        $ppn = (int) round($subtotal * 0.11);
        $insurance = 0;
        $grand_total = $subtotal + $ppn + $insurance;

$payload = [
    'product_id' => $product->id,
    'product_name' => $product->name,
    'product_image' => $product->thumbnail_url,
    'price_per_day' => $pricePerDay,
    'unit' => $unit,
    'days' => $days,
    'start_date' => $startDate,
    'end_date' => $endDate,
    'delivery_method' => $data['delivery_method'] ?? 'pickup',
    'store_id' => $data['store_id'] ?? null,
    // simpan alamat (home delivery)
    'address' => $data['address'] ?? null,
    'address_detail' => $data['address_detail'] ?? null,
    // jangan simpan full_name / phone di sini (akan diisi di checkout)
    'subtotal' => $subtotal,
    'ppn' => $ppn,
    'insurance' => $insurance,
    'grand_total' => $grand_total,
    'saved_at' => now()->toDateTimeString(),
];

        $sessionKey = "booking.{$product->id}";
        session()->put($sessionKey, $payload);
        session()->save();

        Log::debug('BOOKING SAVED TO SESSION', [
            'session_key' => $sessionKey,
            'payload' => $payload,
            'all_session_keys' => array_keys(session()->all()),
        ]);

        return redirect()->route('front.checkout', $product->slug);
    }

//⁡⁣⁣⁢===============================================================⁡⁡ 
public function checkout(Product $product)
{
    // Ambil booking dari session
    $booking = session("booking.{$product->id}") ?? null;

    // Ambil daftar payment methods aktif
    $paymentMethods = \App\Models\PaymentMethod::where('active', true)
                        ->orderBy('sort_order', 'asc')
                        ->get();

    return view('front.checkout', compact('product', 'booking', 'paymentMethods'));
}


//⁡⁣⁣⁢===============================================================⁡⁡ 
public function checkout_store(Request $req)
{


    Log::debug('CHECKOUT_STORE: hasFile', [
    'hasFile' => $req->hasFile('payment_proof'),
    'file' => $req->hasFile('payment_proof') ? [
        'class' => get_class($req->file('payment_proof')),
        'clientName' => $req->file('payment_proof')->getClientOriginalName(),
        'mime' => $req->file('payment_proof')->getClientMimeType(),
        'size' => $req->file('payment_proof')->getSize(),
    ] : null,
]);
    // Validasi input yang memang dikirim dari form
    $data = $req->validate([
        'product_id' => 'required|exists:products,id',
        'full_name' => 'required|string',
        'phone' => 'required|string',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'payment_proof' => 'required|file|image|max:2048',
    ]);

    $product = Product::findOrFail($data['product_id']);

    // ambil booking dari session (server-side truth)
    $booking = session("booking.{$product->id}") ?? null;
    if (!$booking) {
        return back()->withErrors(['booking' => 'Booking session tidak ditemukan. Silakan ulang proses booking.']);
    }

    // pastikan jika home_delivery -> address ada
    if (($booking['delivery_method'] ?? 'pickup') === 'home_delivery') {
        if (empty($booking['address'])) {
            return redirect()->route('front.booking', $product->slug)
                     ->withErrors(['delivery' => 'Lengkapi alamat pengiriman di halaman booking.']);
        }
    }

    // hitung ulang harga dari data session (secure)
    $pricePerDay = isset($booking['price_per_day']) ? (int)$booking['price_per_day'] : (int)$product->price;
    $unit = isset($booking['unit']) ? (int)$booking['unit'] : 1;
    $days = isset($booking['days']) ? (int)$booking['days'] : 1;

    $subtotal = $pricePerDay * $unit * $days;
    $ppn = (int) round($subtotal * 0.11);
    $insurance = isset($booking['insurance']) ? (int)$booking['insurance'] : (5000 * $days);
    $grandTotal = $subtotal + $ppn + $insurance;

    // buat transaksi sesuai model Transaction lo
    $tx = new Transaction();
    $tx->name = $data['full_name'];
    $tx->phone_number = $data['phone'];
    $tx->address = $booking['address'] ?? null;
    $tx->started_at = $booking['start_date'] ?? null;
    $tx->ended_at = $booking['end_date'] ?? null;
    $tx->duration = $days;
    $tx->unit = $unit;
    $tx->delivery_type = $booking['delivery_method'] ?? 'pickup';
    $tx->store_id = $booking['store_id'] ?? null;
    $tx->product_id = $product->id;
    $tx->total_amount = $grandTotal;
    $tx->is_paid = false;
    $tx->payment_method_id = $data['payment_method_id'];
    // $tx->trx_id = $this->generateTrxId();
    $tx->status = 'payment_review';


    // jika ada file payment_proof -> assign agar mutator upload ke Cloudinary
    if ($req->hasFile('payment_proof')) {
        $tx->proof_url = $req->file('payment_proof'); // mutator akan handle upload & public_id
    }

    $tx->save();

    // hapus session booking (kata lu pakai key booking.{id})
    session()->forget("booking.{$product->id}");

    return redirect()->route('front.success.booking', $tx->id);
}


//⁡⁣⁣⁢===============================================================⁡⁡ 
public function success_booking(Transaction $transaction)
{
    $transaction->load('product', 'store');
    return view('front.success_booking', compact('transaction'));
}


//⁡⁣⁣⁢===============================================================⁡⁡ 
    public function transactions()
    {
        return view('front.transactions'); // berisi form cek booking (id + phone)
    }

//⁡⁣⁣⁢===============================================================⁡⁡ 
    public function transaction_details(Request $req)
    {
        $data = $req->validate([
            'transaction_code' => 'required|string',
            'phone' => 'required|string',
        ]);

$tx = Transaction::where('trx_id', $data['transaction_code'])
        ->where('phone_number', $data['phone'])
        ->with('product', 'store')
        ->first();


        if (!$tx) {
            return back()->withErrors(['notfound' => 'Transaksi tidak ditemukan. Periksa ID booking dan nomor telepon.']);
        }

        return view('front.transaction_details', compact('tx'));
    }

public function transaction_detail(Transaction $transaction)
{
    $transaction->load('product', 'store');

    return view('front.transaction_details', [
        'tx' => $transaction
    ]);
}



    //⁡⁣⁣⁢===============================================================⁡⁡ 
public function category(Category $category)
{
    $brands = $category->brands()->get();

    $products = Product::where('category_id', $category->id)
        ->latest()
        ->paginate(12);

    return view('front.category', compact(
        'category',
        'brands',
        'products'
    ));
}



public function categoryByBrand(Category $category, Brand $brand)
{
    $brands = $category->brands()->get();

    $products = Product::where('category_id', $category->id)
        ->where('brand_id', $brand->id)
        ->latest()
        ->paginate(12);

    return view('front.category', compact(
        'category',
        'brands',
        'products',
        'brand'
    ));
}




//⁡⁣⁣⁢===============================================================⁡⁡ 
    public function brand(Brand $brand)
    {
        $products = $brand->products()->paginate(12);
        return view('front.brand', compact('brand','products'));
    }

// /⁡⁣⁣⁢/===============================================================⁡⁡⁡ 
    private function generateTrxId()
    {
        return 'SW' . rand(1000, 9999);
    }

}
