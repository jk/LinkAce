<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

/**
 * Class ResetPasswordCommand
 *
 * @package App\Console\Commands
 */
class ResetPasswordCommand extends Command
{
    use AsksForUser;

    protected $signature = 'reset-password';

    public function handle(): void
    {
        $this->line('This tool allows you to reset the password for any user.');

        $this->askForUserEmail();
        $this->resetUserPassword();
    }

    protected function resetUserPassword()
    {
        do {
            $newPassword = $this->secret('Please enter a new password for this user');

            $validator = Validator::make(['password' => $newPassword], [
                'password' => 'required|string|min:10',
            ]);

            if ($validator->invalid()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->warn($error);
                }
            }
        } while ($validator->invalid());

        $this->user->password = bcrypt($newPassword);
        $this->user->save();

        $this->info('Password successfully changed. You can now login.');
    }
}
