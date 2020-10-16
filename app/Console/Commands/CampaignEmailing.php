<?php

namespace App\Console\Commands;

use App\Campaign;
use App\Email;
use App\Entretien;
use App\Entretien_user;
use App\Http\Mail\MailerController;
use App\User;
use Carbon\Carbon;
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
    $now = date("Y-m-d H:i", strtotime(Carbon::now()->addHour()));
    $campaignEmails = \DB::table('campaigns')
      ->select('campaigns.*', 'entretiens.date_limit')
      ->join('entretiens', 'entretiens.id', '=', 'campaigns.entretien_id')
      ->where('campaigns.sheduled_at', '<=', $now)
      ->where('entretiens.date_limit', '>=', $now)
      ->get();

    if (count($campaignEmails) <= 0) {
      $this->info('Nothing found to send !');
      return;
    }

    $i = 0;
    foreach ($campaignEmails as $campaignEmail) {
      $user = User::where('email', $campaignEmail->receiver)->first();
      $isManager = $user->children->count() > 0;
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
      $campaignEmailModel = Campaign::find($campaignEmail->id);
      $campaignEmailModel->status = "Envoyé";
      $campaignEmailModel->sent_at = date('Y-m-d H:i');
      $campaignEmailModel->save();
      $i ++;
    }
    $this->info("$i email(s) sent to users !");
  }
}
