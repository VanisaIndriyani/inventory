<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedInteger('stock_jan')->default(0);
            $table->unsignedInteger('stock_feb')->default(0);
            $table->unsignedInteger('stock_mar')->default(0);
            $table->unsignedInteger('stock_apr')->default(0);
            $table->unsignedInteger('stock_may')->default(0);
            $table->unsignedInteger('stock_jun')->default(0);
            $table->unsignedInteger('stock_jul')->default(0);
            $table->unsignedInteger('stock_aug')->default(0);
            $table->unsignedInteger('stock_sep')->default(0);
            $table->unsignedInteger('stock_oct')->default(0);
            $table->unsignedInteger('stock_nov')->default(0);
            $table->unsignedInteger('stock_dec')->default(0);

            $table->decimal('usage_rate', 10, 2)->default(0);
            $table->unsignedInteger('lead_time')->default(0);
            $table->unsignedInteger('rop_alert')->default(0);

            $table->unsignedInteger('day_1')->default(0);
            $table->unsignedInteger('day_2')->default(0);
            $table->unsignedInteger('day_3')->default(0);
            $table->unsignedInteger('day_4')->default(0);
            $table->unsignedInteger('day_5')->default(0);
            $table->unsignedInteger('day_6')->default(0);
            $table->unsignedInteger('day_7')->default(0);
            $table->unsignedInteger('day_8')->default(0);
            $table->unsignedInteger('day_9')->default(0);
            $table->unsignedInteger('day_10')->default(0);
            $table->unsignedInteger('day_11')->default(0);
            $table->unsignedInteger('day_12')->default(0);
            $table->unsignedInteger('day_13')->default(0);
            $table->unsignedInteger('day_14')->default(0);
            $table->unsignedInteger('day_15')->default(0);
            $table->unsignedInteger('day_16')->default(0);
            $table->unsignedInteger('day_17')->default(0);
            $table->unsignedInteger('day_18')->default(0);
            $table->unsignedInteger('day_19')->default(0);
            $table->unsignedInteger('day_20')->default(0);
            $table->unsignedInteger('day_21')->default(0);
            $table->unsignedInteger('day_22')->default(0);
            $table->unsignedInteger('day_23')->default(0);
            $table->unsignedInteger('day_24')->default(0);
            $table->unsignedInteger('day_25')->default(0);
            $table->unsignedInteger('day_26')->default(0);
            $table->unsignedInteger('day_27')->default(0);
            $table->unsignedInteger('day_28')->default(0);
            $table->unsignedInteger('day_29')->default(0);
            $table->unsignedInteger('day_30')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn([
                'stock_jan',
                'stock_feb',
                'stock_mar',
                'stock_apr',
                'stock_may',
                'stock_jun',
                'stock_jul',
                'stock_aug',
                'stock_sep',
                'stock_oct',
                'stock_nov',
                'stock_dec',
                'usage_rate',
                'lead_time',
                'rop_alert',
                'day_1',
                'day_2',
                'day_3',
                'day_4',
                'day_5',
                'day_6',
                'day_7',
                'day_8',
                'day_9',
                'day_10',
                'day_11',
                'day_12',
                'day_13',
                'day_14',
                'day_15',
                'day_16',
                'day_17',
                'day_18',
                'day_19',
                'day_20',
                'day_21',
                'day_22',
                'day_23',
                'day_24',
                'day_25',
                'day_26',
                'day_27',
                'day_28',
                'day_29',
                'day_30',
            ]);
        });
    }
};
