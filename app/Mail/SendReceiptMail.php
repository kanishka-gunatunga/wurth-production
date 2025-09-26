<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $pdfName;
    public $customer;

    public function __construct($pdfContent, $pdfName, $customer)
    {
        $this->pdfContent = $pdfContent;
        $this->pdfName = $pdfName;
        $this->customer = $customer;
    }

    public function build()
    {
        return $this->subject('Wurth Collection Receipt')
            ->view('email_templates.collection_receipt', ['customer' => $this->customer])
            ->attachData($this->pdfContent, $this->pdfName, [
                'mime' => 'application/pdf',
            ]);
    }
}

?>