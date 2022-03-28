<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name', 150);
            $table->integer('author')->nullable();
            $table->smallInteger('publish_year')->nullable();
            $table->string('description', 2000)->nullable();
            $table->mediumblob('cover')->nullable();
            $table->integer('category')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->boolean('hidden')->default(0);
            $table->timestamps()->default('current_timestamp()');
            
            $table->foreign('author', 'books_ibfk_1')->references('id')->on('author');
            $table->foreign('added_by', 'books_ibfk_2')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('category', 'books_ibfk_3')->references('id')->on('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
