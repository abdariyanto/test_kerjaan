<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Table "users" dengan kolom "name", "email", "password", dan "role":
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        // Table "news" dengan kolom "title", "description", dan "image":
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->timestamps();
        });
        // Table "log" dengan kolom "title", "description", dan "image":
        

        // Table "comments" dengan kolom "content", "likes_count", "dislikes_count", "user_id", dan "news_id":
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->integer('likes_count')->default(0);
            $table->integer('dislikes_count')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('news_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
        });
        Schema::create('log_news', function (Blueprint $table) {
            $table->id();
            $table->integer('news_id');
            $table->integer('user_id');
            $table->enum('event', ['create', 'update', 'delete'])->default('create');
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
        Schema::dropIfExists('comments');
        Schema::dropIfExists('news');
        Schema::dropIfExists('users');
        Schema::dropIfExists('log_news');
    }
}
