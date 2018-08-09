<?php

namespace App\Jobs;

use App\Entity\MailList;
use DrewM\MailChimp\MailChimp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateMailList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $listId;
    private $data;

    /**
     * Create a new job instance.
     *
     * @param String $listId
     * @param array $data
     */
    public function __construct(String $listId, Array $data)
    {
        $this->listId = $listId;
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
        $response = $mailChimp->patch("lists/{$this->listId}", $this->data);
        $list = MailList::where('id', 'like', $this->listId)->first();
        $list->update($response);
    }
}
