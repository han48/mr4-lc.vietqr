<?php

namespace Mr4Lc\VietQr\Console\Commands;

use Illuminate\Console\Command;
use Mr4Lc\VietQr\Models\VietqrServiceCode;

class UpdateServiceCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vietqr:service_code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update VietQR Service code';

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
        if (VietqrServiceCode::where('name', 'account')->count() === 0) {
            VietqrServiceCode::insert([
                'name' => 'account',
                'value' => 'QRIBFTTA',
            ]);
        }
        if (VietqrServiceCode::where('name', 'card')->count() === 0) {
            VietqrServiceCode::insert([
                'name' => 'card',
                'value' => 'QRIBFTTC',
            ]);
        }
        return 0;
    }
}
