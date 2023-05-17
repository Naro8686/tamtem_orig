<?php

use Illuminate\Database\Seeder;

class DealsQuestionsHeadersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\DealQuestionHeader::create(['name' => 'Объем',  'slug' => 'dqh_volume']);
        \App\Models\DealQuestionHeader::create(['name' => 'Спецификация', 'slug' => 'dqh_specification']);
        \App\Models\DealQuestionHeader::create(['name' => 'Документы подтверждающие качество', 'slug' => 'dqh_doc_confirm_quality']);
        \App\Models\DealQuestionHeader::create(['name' => 'Логистика', 'slug' => 'dqh_logistics']);
        \App\Models\DealQuestionHeader::create(['name' => 'Тип сделки', 'slug' => 'dqh_type_deal']);
        \App\Models\DealQuestionHeader::create(['name' => 'Условия оплаты', 'slug' => 'dqh_payment_term']);
        \App\Models\DealQuestionHeader::create(['name' => 'Способ оплаты', 'slug' => 'dqh_payment_method']);
        \App\Models\DealQuestionHeader::create(['name' => 'Минимальная партия', 'slug' => 'dqh_min_party']);
    }
}
