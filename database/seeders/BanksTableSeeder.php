<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['code' => '001', 'name' => 'Banco do Brasil S.A.', 'ispb' => '00000000'],
            ['code' => '104', 'name' => 'Caixa Econômica Federal', 'ispb' => '00360305'],
            ['code' => '237', 'name' => 'Banco Bradesco S.A.', 'ispb' => '60746948'],
            ['code' => '341', 'name' => 'Itaú Unibanco S.A.', 'ispb' => '60701190'],
            ['code' => '033', 'name' => 'Banco Santander (Brasil) S.A.', 'ispb' => '90400888'],
            ['code' => '260', 'name' => 'Nubank', 'ispb' => '18236120'],
            ['code' => '077', 'name' => 'Banco Inter', 'ispb' => '00416968'],
            ['code' => '748', 'name' => 'Banco Cooperativo Sicredi S.A.', 'ispb' => '01181521'],
            ['code' => '756', 'name' => 'Banco Cooperativo do Brasil S.A. - Sicoob', 'ispb' => '02038232'],
            ['code' => '208', 'name' => 'Banco BTG Pactual S.A.', 'ispb' => '30306294'],
            ['code' => '041', 'name' => 'Banco do Estado do Rio Grande do Sul S.A. (Banrisul)', 'ispb' => '92702067'],
            ['code' => '422', 'name' => 'Banco Safra S.A.', 'ispb' => '58160789'],
            ['code' => '212', 'name' => 'Banco Original S.A.', 'ispb' => '92894922'],
            ['code' => '336', 'name' => 'Banco C6 S.A. (C6 Bank)', 'ispb' => '31872495'],
            ['code' => '623', 'name' => 'Banco PAN S.A.', 'ispb' => '59285411'],
        ];
        foreach ($banks as $bank) {
            DB::table('banks')->updateOrInsert(['code' => $bank['code']], $bank);
            echo "Inserido: {$bank['name']}\n";
        }
    }
}
