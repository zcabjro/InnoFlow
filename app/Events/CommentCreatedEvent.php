<?php

namespace App\Events;

use App\Models\VstsProject;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class CommentCreatedEvent
{
    use InteractsWithSockets, SerializesModels;

    public $vstsProject;

    public function __construct( VstsProject $vstsProject )
    {
        $this -> vstsProject = $vstsProject;
    }
}
