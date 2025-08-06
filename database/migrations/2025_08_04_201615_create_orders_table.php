<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // FK to users table
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // FK to addresses table
            $table->unsignedBigInteger('delivered_address_id')->nullable();
            $table->foreign('delivered_address_id')->references('id')->on('addresses')->onDelete('set null');

            // Order details
            $table->decimal('total_price', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamp('ordered_at')->useCurrent();

            $table->timestamps();
            $table->softDeletes(); // adds deleted_at column
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}

