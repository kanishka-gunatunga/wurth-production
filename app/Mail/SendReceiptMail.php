<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class SendReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $pdfPath;
    public $isDuplicate;

    /**
     * @param $payment  Single payment model
     * @param string $pdfPath Path to PDF
     * @param bool $isDuplicate
     */
    public function __construct($payment, $pdfPath, $isDuplicate = false)
    {
        $this->payment = $payment;
        $this->pdfPath = $pdfPath;
        $this->isDuplicate = $isDuplicate;
    }

    public function build()
    {
        $subject = $this->isDuplicate
            ? 'Wurth Lanka | Duplicate Payment Receipt'
            : 'Wurth Lanka | Payment Receipt';

        $email = $this->subject($subject)
            ->view('email_templates.collection_receipt', [
                'payment'      => $this->payment,
                'customer'     => $this->payment->invoice->customer ?? null,
                'is_duplicate' => $this->isDuplicate,
            ]);

        // Attach the PDF only if exists
        if ($this->pdfPath && File::exists($this->pdfPath)) {
            $email->attach($this->pdfPath, [
                'as'   => basename($this->pdfPath),
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}
