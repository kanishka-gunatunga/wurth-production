<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\InvoicePayments;

class SendReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $pdfPath;

    public function __construct(InvoicePayments $payment, $pdfPath)
    {
        $this->payment = $payment;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Wurth Collection Receipt')
            ->view('email_templates.collection_receipt', ['customer' => $this->payment->invoice->customer])
            ->attach($this->pdfPath, [
                'as' => basename($this->pdfPath),
                'mime' => 'application/pdf',
            ]);
    }
}
