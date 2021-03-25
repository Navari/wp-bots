<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('featured_image', 500)->nullable();
            $table->string('title', 1000)->index();
            $table->text('summary')->nullable();
            $table->longText('description');
            $table->text('tags')->nullable();
            $table->json('gallery')->nullable();
            $table->string('origin_url',1000)->nullable();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('crawl_site_id')->index();
            $table->foreign('category_id')->on('categories')->references('id')->cascadeOnDelete();
            $table->foreign('crawl_site_id')->on('crawl_sites')->references('id')->cascadeOnDelete();
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
        Schema::dropIfExists('news');
    }
}
