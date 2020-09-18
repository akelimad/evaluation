<?php

namespace App\Console\Commands;

use App\Helpers\Base;
use App\User;
use Illuminate\Console\Command;

class SendMentorEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:mentor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to notify mentors to interview their contributors';

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
        $mentors = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', '=', 'MENTOR');
        })->get();

        foreach ($mentors as $mentor) {
            $password = Base::getRandomString();
            $mentor->password = bcrypt($password);
            $mentor->save();
            Mail::send('emails.mentor_invitation', [
                'mentor' => $mentor,
                'password' => $password,
                'endDate' => $entretien->date_limit
            ], function ($m) use ($mentor) {
                $m->from('contact@lycom.ma', 'E-entretien');
                $m->to($mentor->email, $mentor->name)->subject('Invitation pour évaluer vos collaborateurs');
            });
        }
        $this->info('An Email was sent successfully to mentors !');
    }
}
