<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrencyWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('currency')->insert([
            'name'      => 'Canadian dollar',
            'char_code' => 'CAD',
            'num_code'  => 124,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 6,
        ]);
        /*             $table->tinyIncrements('id')->unsigned();
            $table->string('name', 100)->unique();
            $table->string('num_code', 3)->unique();
            $table->string('char_code', 3)->unique();
            $table->boolean('is_top')->default(false);
            $table->boolean('active')->default(false);
            $table->integer('ordering')->unsigned();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
 */
        \DB::table('currency')->insert([
            'name'      => 'Hong Kong dollar',
            'char_code' => 'HKD',
            'num_code'  => 344,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 13,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Icelandic króna',
            'char_code' => 'ISK',
            'num_code'  => 352,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 11,

        ]);
        \DB::table('currency')->insert([
            'name'      => 'Philippine peso',
            'char_code' => 'PHP',
            'num_code'  => 608,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 12,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Danish krone',
            'char_code' => 'DKK',
            'num_code'  => 208,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 14,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Hungarian forint',
            'char_code' => 'HUF',
            'num_code'  => 348,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 15,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Czechoslovak koruna',
            'char_code' => 'CZK',
            'num_code'  => 200,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 16,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Australian dollar',
            'char_code' => 'AUD',
            'num_code'  => 036,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 3,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Romanian leu',
            'char_code' => 'RON',
            'num_code'  => 642,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 17,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Swedish krona/kronor',
            'char_code' => 'SEK',
            'num_code'  => 752,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 18,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Indonesian rupiah',
            'char_code' => 'IDR',
            'num_code'  => 360,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 19,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Indian rupee',
            'char_code' => 'INR',
            'num_code'  => 356,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 20,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Brazilian cruzeiro',
            'char_code' => 'BRL',
            'num_code'  => 076,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 21,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Russian ruble',
            'char_code' => 'RUB',
            'num_code'  => 810,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 22,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Croatian kuna',
            'char_code' => 'HRK',
            'num_code'  => 191,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 23,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Japanese yen',
            'char_code' => 'JPY',
            'num_code'  => 392,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 24,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Thai baht',
            'char_code' => 'THB',
            'num_code'  => 764,
            'is_top'    => false,
            'active'    => false,
            'ordering'  => 25,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Swiss franc',
            'char_code' => 'CHF',
            'num_code'  => 756,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 26,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Singapore dollar',
            'char_code' => 'SGD',
            'num_code'  => 702,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 27,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Polish zloty',
            'char_code' => 'PLN',
            'num_code'  => 616,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 28,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Bulgarian lev',
            'char_code' => 'BGN',
            'num_code'  => 975,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 29,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Turkish lira',
            'char_code' => 'TRY',
            'num_code'  => 979,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 30,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Chinese yuan',
            'char_code' => 'CNY',
            'num_code'  => 156,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 31,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Norwegian krone',
            'char_code' => 'NOK',
            'num_code'  => 578,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 32,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'New Zealand dollar',
            'char_code' => 'NZD',
            'num_code'  => 544,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 33,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'South African rand',
            'char_code' => 'ZAR',
            'num_code'  => 710,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 34,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'United States dollar',
            'char_code' => 'USD',
            'num_code'  => 840,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 1,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Mexican peso',
            'char_code' => 'MXN',
            'num_code'  => 484,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 35,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Israeli new shekel',
            'char_code' => 'ILS',
            'num_code'  => 376,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 36,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Pound sterling',
            'char_code' => 'GBP',
            'num_code'  => 826,
            'is_top'    => true,
            'active'    => true,
            'ordering'  => 2,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'South Korean won',
            'char_code' => 'KRW',
            'num_code'  => 410,
            'is_top'    => false,
            'active'    => true,
            'ordering'  => 37,
        ]);
        \DB::table('currency')->insert([
            'name'      => 'Malaysian ringgit',
            'char_code' => 'MYR',
            'num_code'  => 458,
            'is_top'    => false,
            'active'    => false,
            'ordering'  => 38,
        ]);
/*
rates
    CAD	1.5633
    HKD	9.424
    ISK	152.1
    PHP	58.43
    DKK	7.4429
    HUF	358.57
    CZK	26.518
    AUD	1.6387
    RON	4.8725
    SEK	10.2578
    IDR	17222.37
    INR	89.6755
    BRL	6.2759
    RUB	90.0679
    HRK	7.5415
    JPY	126.44
    THB	36.672
    CHF	1.0822
    SGD	1.6206
    PLN	4.4769
    BGN	1.9558
    TRY	9.4636
    CNY	7.9421
    NOK	10.6598
    NZD	1.7254
    ZAR	18.4674
    USD	1.2159
    MXN	24.1091
    ILS	3.972
    GBP	0.90282
    KRW	1320.6
    MYR	4.9366
base	"EUR"
date	"2020-12-04" */
    }
}
