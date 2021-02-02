<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class currencyRatesImportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $successResult;
    public $resultMessage;
    public $additiveVars;

    // \Mail::to($myEmail)->send(new currencyRatesImportEmail($title .' with success ', false, 'Main currency is not set.  Check Settings page !'));
    public function __construct($title, $successResult, $resultMessage, $additiveVars)
    {
        $this->title         = $title;
        $this->successResult = $successResult;
        $this->additiveVars  = $additiveVars;
        $this->resultMessage = $resultMessage;
    }

    public function build()
    {
        return $this->markdown('emails.currencyRatesImportEmail')
                    ->with('title', $this->title)
                    ->with('successResult', $this->successResult)
                    ->with('additiveVars', $this->additiveVars)
                    ->with('resultMessage', $this->resultMessage);

    }

    /*    public function __construct()
        {
            //
        }

        public function build()
        {
            return $this->markdown('emails.currencyRatesImportEmail');
        }*/
}
