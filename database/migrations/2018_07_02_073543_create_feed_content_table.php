<?php declare(strict_types = 1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedContentTable extends Migration
{
    public function up(): void
    {
        Schema::create('feed_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('feed_id')->unsigned();
            $table->string('title', 255);
            $table->string('description', 255);
            $table->text('content');
            $table->string('permalink', 255)->unique();
            $table->boolean('read')->default(false);
            $table->timestamps();
        });

        Schema::table('feed_content', function (Blueprint $table) {
            $table->foreign('feed_id')->references('id')->on('feeds')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('feed_content', function (Blueprint $table) {
            $table->dropForeign('feed_content_feed_id_foreign');
        });

        Schema::dropIfExists('feed_content');
    }
}