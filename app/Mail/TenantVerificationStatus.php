<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantVerificationStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function build()
    {
        $subject = $this->emailData['status'] === 'verified' 
            ? 'Your Tenant Account Has Been Approved!' 
            : 'Tenant Account Status Update';

        return $this->subject($subject)
                    ->view('emails.tenant-verification-status');
    }
} 