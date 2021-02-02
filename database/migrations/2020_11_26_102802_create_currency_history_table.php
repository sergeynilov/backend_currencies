<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_history', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->date('day')->nullable();

            $table->tinyInteger('currency_id')->unsigned();
            $table->foreign('currency_id')->references('id')->on('currency')->onDelete('CASCADE');
            $table->smallInteger('nominal')->unsigned();
            $table->decimal('value', 18, 10);

            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at'], 'currency_history_created_at_index');
            $table->unique(['day', 'currency_id'], 'currency_history_day_currency_id_unique');
            $table->index(['currency_id', 'day'], 'currency_history_currency_id_day_index');
        });
//        Artisan::call('db:seed', array('--class' => 'CurrencyHistoryTableSeeder'));


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_history');
    }
}
