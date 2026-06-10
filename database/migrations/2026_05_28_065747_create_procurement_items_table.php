<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_draft_id')->constrained('procurement_drafts')->onDelete('cascade');
            $table->enum('type', ['asset', 'bhp']);
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            $table->string('purchase_link')->nullable();
            $table->foreignId('replaced_asset_id')->nullable()->constrained('assets')->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('received_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procurement_items');
    }
};
