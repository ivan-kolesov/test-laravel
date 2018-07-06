<?php declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedPostTable extends Migration
{
    public function up(): void
    {
        Schema::create('feed_post', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feed_id')->unsigned();
            $table->string('title', 255);
            $table->string('description', 1024)->nullable();
            $table->text('content')->nullable();
            $table->string('permalink', 255)->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();
            $table->index(['created_at', 'feed_id', 'read'], 'feed_post_idx');
            $table->unique(['feed_id', 'permalink'], 'feed_post_permalink_unique');
        });

        Schema::table('feed_post', function (Blueprint $table) {
            $table->foreign('feed_id')->references('id')->on('feeds')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('feed_post', function (Blueprint $table) {
            $table->dropForeign('feed_post_feed_id_foreign');
        });

        Schema::dropIfExists('feed_post');
    }
}