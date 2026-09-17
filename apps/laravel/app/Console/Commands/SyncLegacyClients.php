<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:sync-legacy-clients')]
#[Description('Command description')]
class SyncLegacyClients extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
