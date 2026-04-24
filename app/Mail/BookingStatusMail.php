<?php 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content; // مهمة للنوع
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        
        public $booking, 
        public $type, 
        public $invoiceUrl = null 
    ) {}

    public function content(): Content
    {
        Log::info("we are inside mail");

        return new Content(
            view: $this->type === 'success' ? 'pdf.booking_success' : 'pdf.booking_failed',
            // العظمة هنا: تمرير البيانات للـ View عشان الـ Blade يقرأهم
            with: [
                'booking' => $this->booking,
                'invoiceUrl' => $this->invoiceUrl,
            ],
        );
    }

    public function attachments(): array
    {
        Log::info("we are inside mail");

        $attachments = [];

        if ($this->type === 'success') {
            // توليد الـ PDF
            $pdf = Pdf::loadView('pdf.invoice', ['booking' => $this->booking]);
            
            // إضافة الملف كمرفق
            $attachments[] = Attachment::fromData(fn () => $pdf->output(), 'Invoice.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}