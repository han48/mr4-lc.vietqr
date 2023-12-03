<?php

namespace Mr4Lc\VietQr\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Laravel\Prompts\Output\ConsoleOutput;
use Mr4Lc\VietQr\Models\VietqrBank;

class UpdateBanksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vietqr:bank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update VietQR Bank List';

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
        $output = new ConsoleOutput();
        $output->writeln("Update VietQR bank list: Start");
        $response = Http::get(config('mr4vietqr.api.banks.url', 'https://api.vietqr.io/v2/banks'));
        $data = $response->json('data');
        foreach ($data as $key => $value) {
            $columns = config('mr4vietqr.api.banks.columns', ['code', 'bin']);
            $values = [];
            foreach ($columns as $column) {
                if (array_key_exists($column, $value)) {
                    $values[$column] = $value[$column];
                } else {
                    $values[$column] = null;
                }
            }
            $bank = VietqrBank::where('code', $value['code'])->first();
            if (isset($bank)) {
                $output->writeln("    Update: " . $value['code']);
                $bank->update($values);
            } else {
                $output->writeln("    Import: " . $value['code']);
                $bank = new VietqrBank($values);
                $bank->save();
            }
        }
        $output->writeln("Update VietQR bank list: End");
        return 0;
    }
}
