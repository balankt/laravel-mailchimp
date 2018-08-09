<?php

namespace App\Jobs;

use App\Entity\MailList;
//use DrewM\MailChimp\MailChimp;
use DrewM\MailChimp\MailChimp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateListMember implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $listId;
    private $memberHash;
    private $data;

    /**
     * Create a new job instance.
     *
     * @param String $listId
     * @param String $memberHash
     * @param array $data
     */
    public function __construct(String $listId, String $memberHash,Array $data)
    {
        $this->listId = $listId;
        $this->memberHash = $memberHash;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param MailChimp $mailChimp
     * @return void
     */
    public function handle(MailChimp $mailChimp)
    {
        $response = $mailChimp->patch("lists/{$this->listId}/members/{$this->memberHash}", $this->data);
        $list = MailList::where('id', 'like', $this->listId)->first();
        $list->update($response);
    }
}
