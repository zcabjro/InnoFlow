<?php

namespace App\Events;

use App\Models\Commit;
use App\Models\VstsProject;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;


class CommitCreatedEvent
{
    use InteractsWithSockets, SerializesModels;

    public $commit;
    public $vstsProject;

    public function __construct( Commit $commit, VstsProject $vstsProject )
    {
        $this -> commit = $commit;
        $this -> vstsProject = $vstsProject;
    }
}
