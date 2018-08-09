<?php

namespace App\Console\Commands;


use App\Entity\ListMember;
use App\Entity\MailList;
use DrewM\MailChimp\MailChimp;
use Illuminate\Console\Command;


class SynchronizeCommand extends Command
{
    protected $signature = 'mailchimp:synchronize';

    protected $description = 'Synchronize data with MailChimp';

    private $mailChimp;

    /**
     * SynchronizeCommand constructor.
     * @param MailChimp $mailChimp
     */
    public function __construct(MailChimp $mailChimp)
    {
        parent::__construct();
        $this->mailChimp = $mailChimp;
    }

    public function handle()
    {
        $lists = $this->mailChimp->get("lists");
        if (!$lists){
            echo 'Error: connection to MailChimp API failed. Check your MailChimp API key'.PHP_EOL;
            return false;
        }
        foreach ($lists['lists'] as $list) {
            $mailList = MailList::where('id', 'like', $list['id'])->first();
            if ($mailList) {
                $mailList->update($list);
            } else {
                MailList::create($list);
            }
            $members = $this->mailChimp->get("lists/{$list['id']}/members");
            foreach ($members['members'] as $item) {
                $member = ListMember::where('id', 'like', $item['id'])->first();
                if ($member) {
                    $member->update($item);
                } else {
                    ListMember::create($item);
                }
            }
        }
        return true;
    }
}
