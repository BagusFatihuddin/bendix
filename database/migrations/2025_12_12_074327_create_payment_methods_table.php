<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->nullable(); // e.g. bank, dana, gopay
            $table->string('name'); // display name e.g. "Bank Transfer (Bendix)"
            $table->string('image')->nullable(); // URL ke Cloudinary atau path
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->text('details')->nullable(); // optional additional text
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
