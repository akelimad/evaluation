<?php

namespace App\Console\Commands;

use App\Email;
use App\Entretien;
use App\Http\Mail\MailerController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Log;

class CampaignReminder extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'campaign:reminder';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command to reminder users to fill in their evaluation';

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
    $now = date("Y-m-d H:i", strtotime(Carbon::now()->addHour()));
    $campaignEmails = \DB::table('campaigns')
      ->select('campaigns.*', 'entretiens.date_limit')
      ->join('entretiens', 'entretiens.id', '=', 'campaigns.entretien_id')
      ->where('campaigns.sheduled_at', '<=', $now)
      ->where('entretiens.date_limit', '>=', $now)
      ->get();

    if ($campaignEmails->count() <= 0) {
      $this->info('Nothing found to send !');
      return;
    }

    $i = 0;
    foreach ($campaignEmails as $campaignEmail) {
      $user = User::where('email', $campaignEmail->receiver)->first();
      $isManager = $user->children != null && $user->children->count() > 0;
      $entretien = Entretien::find($campaignEmail->entretien_id);
      // check deadlie
      if (!$isManager && $entretien->date > $now) continue;

      //check if user completed answers
      $userAnswered = false;
      if ($isManager) {
        $managersEntretiens = Entretien_user::where('entretien_id', $entretien->id)
          ->where('mentor_id', $user->id)
          ->where('mentor_submitted', '!=', 2)
          ->get();
        if ($managersEntretiens->count() == 0) $userAnswered = true;
      } else {
        $answered = Entretien::answered($entretien->id, $user->id);
        if ($answered) $userAnswered = true;
      }
      if ($userAnswered) continue;

      $emailTemplate = Email::find($campaignEmail->email_id);
      MailerController::send($user, $entretien, $emailTemplate);
      $i++;
    }
    $this->info("$i email(s) sent to users !");
  }
}
