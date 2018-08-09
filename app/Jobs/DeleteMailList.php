<?php

namespace App\Jobs;

use DrewM\MailChimp\MailChimp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteMailList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $listId;

    /**
     * Create a new job instance.
     *
     * @param String $listId
     */
    public function __construct(String $listId)
    {
        $this->listId = $listId;
    }

    /**
     * Execute the job.
     *
     * @param MailChimp $mailChimp
     * @return void
     */
    public function handle(MailChimp $mailChimp)
    {
        $mailChimp->delete("lists/{$this->listId}");
    }
}
