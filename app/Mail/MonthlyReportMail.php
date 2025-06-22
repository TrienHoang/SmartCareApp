<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Statistic;

class MonthlyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public Statistic $stat;
    public string $attachmentPath;

    public function __construct(Statistic $stat, string $attachmentPath)
    {
        $this->stat = $stat;
        $this->attachmentPath = $attachmentPath;
    }

    public function build(): self
    {
        return $this->subject('Báo cáo thống kê ' . $this->stat->type)
            ->markdown('emails.reports.monthly')
            ->attach($this->attachmentPath);
    }
}
