<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdateAmbassadorRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rankings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to update the cache of rankings.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ambos = User::ambassadors()->get();

        $bar = $this->output->createProgressBar($ambos->count());
        $bar->start();

        $ambos->each(function (User $user) use ($bar) {
            Redis::zadd('rankings', (float) $user->revenue, $user->name);

            $bar->advance();
        });

        $bar->finish();
    }
}
