<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendParentLinkEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $parent;
    protected $child;

    public function __construct($parent, $child)
    {
        $this->parent = $parent;
        $this->child = $child;
    }

    public function handle()
    {
        $to = $this->parent->email;
        Mail::raw("You have been linked to child: {$this->child->first_name} {$this->child->last_name}", function($m) use ($to){
            $m->to($to)->subject('Child linked to your account');
        });
    }
}
