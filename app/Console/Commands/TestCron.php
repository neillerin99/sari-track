<?php

namespace App\Console\Commands;

use App\Models\CronTest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Faker\Factory as Faker;

class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sample Test';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
              $faker = Faker::create();
            CronTest::create([
                'message' => $faker->sentence .now(),
            ]);
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
