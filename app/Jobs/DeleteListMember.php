<?php

namespace App\Jobs;

use DrewM\MailChimp\MailChimp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteListMember implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $listId;
    private $memberHash;

    /**
     * Create a new job instance.
     *
     * @param String $listId
     * @param String $memberHash
     */
    public function __construct(String $listId, String $memberHash)
    {
        $this->listId = $listId;
        $this->memberHash = $memberHash;
    }

    /**
     * Execute the job.
     *
     * @param MailChimp $mailChimp
     * @return void
     */
    public function handle(MailChimp $mailChimp)
    {
        $mailChimp->delete("lists/{$this->listId}/members/{$this->memberHash}");
    }
}
