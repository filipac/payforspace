<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;

class SyncBlockchainTransactions extends Command
{
    const SECONDS_SLEEP = 10;

    protected $signature = 'sync:blockchain-transaction';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $url = config('payforspace.elrond_api').'transactions';

        while(true) {
            $this->info('Checking for new transactions');
            $response = \Http::get($url, [
                'from' => 0,
                'size' => 200,
                'receiver' => config('payforspace.elrond_wallet_address'),
                'condition' => 'should',
                'fields' => 'txHash,receiver,value,status,data,timestamp',
                //            'before' => '',
                //            'after' => '',
            ]);
            $data = $response->json();
            $hashes = collect($data)->map(fn($tr) => $tr['txHash'])->toArray();
            $localTransactions = Transaction::query()->whereIn('hash', $hashes)->get(['id', 'hash']);

            foreach($data as $transaction) {
                if($local = $localTransactions->first(fn($el) => $el->hash == $transaction['txHash'])) {
//                    $this->info('Skipping transaction #'.$local->id);
                    continue;
                }

                $informationResponse = \Http::get(config('payforspace.elrond_gateway').'transaction/'.$transaction['txHash']);
                $informationResponse = $informationResponse->json();

                Transaction::create([
                    'hash' => $transaction['txHash'],
                    'value' => $transaction['value'],
                    'data' => isset($transaction['data']) ? base64_decode($transaction['data']) : null,
                    'object' => $informationResponse['data'],
                ]);

                $this->info('Copied txHash "'.$transaction['txHash'].'" locally');
            }

            sleep(self::SECONDS_SLEEP);
        }
    }
}
