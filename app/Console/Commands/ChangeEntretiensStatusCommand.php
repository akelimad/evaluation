<?php

namespace App\Console\Commands;

use App\Entretien;
use Illuminate\Console\Command;

class ChangeEntretiensStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entretiens:change-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $entretiens = Entretien::all();
        if (empty($entretiens)) return;
        $i = 0;
        foreach ($entretiens as $e) {
            if (date('Y-m-d H:i', strtotime('now')) > $e->date_limit && $e->status != Entretien::EXPIRED_STATUS) {
                $e->status = Entretien::EXPIRED_STATUS;
                $e->save();
                $i ++;
            }
        }
        $this->info("Status was changed to expired for $i entretiens");
    }
}
