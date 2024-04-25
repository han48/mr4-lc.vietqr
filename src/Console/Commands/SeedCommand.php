<?php

namespace Mr4Lc\VietQr\Console\Commands;

use Illuminate\Console\Command;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vietqr:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update VietQR seed';

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
        (new UpdateBanksCommand())->handle();
        (new UpdateServiceCodesCommand())->handle();
        return 0;
    }
}
