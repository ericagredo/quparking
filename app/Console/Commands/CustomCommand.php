<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PushNotificationController;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:user_can_not_exit_from_booking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'user_can_not_exit_from_booking';

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
        PushNotificationController::user_can_not_exit_from_booking();


    }
}
