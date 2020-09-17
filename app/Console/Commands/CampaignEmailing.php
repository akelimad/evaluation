<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CampaignEmailing extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'campaign';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command to notify mentors and collaborators to interview';

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
    Mail::send('emails.test', [], function ($m) {
      $m->from('contact@lycom.ma', 'E-entretien');
      $m->to('akel.dev@gmail.com', 'akel')->subject('test subject');
    });

    $this->info('An email was sent successfully !');
  }
}
