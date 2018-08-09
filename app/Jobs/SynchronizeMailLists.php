<?php

namespace App\Jobs;

use App\Entity\MailList;
use DrewM\MailChimp\MailChimp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SynchronizeMailLists implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param MailChimp $mailChimp
     * @return void
     */
    public function handle(MailChimp $mailChimp)
    {
        $data = $mailChimp->get("lists");
        foreach ($data['lists'] as $list) {
            $mailList = MailList::where('id', 'like', $list['id'])->first();
            if ($mailList) {
                $mailList->update($list);
            } else {
                MailList::create($list);
            }
        }
    }
}
