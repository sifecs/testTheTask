<?php

namespace Database\Seeders;

use App\Models\GuestBook;
use DB;
use Drandin\ClosureTableComments\ClosureTableService;
use Drandin\ClosureTableComments\Commentator;
use Illuminate\Database\Seeder;

class GuestBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GuestBook::createFake(120);
        GuestBook::answerFake();
    }
}
