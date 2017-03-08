<?php

namespace App\Events;

use App\Models\CodeReview;
use App\Models\VstsProject;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;


class CodeReviewActiveEvent
{
    use InteractsWithSockets, SerializesModels;

    public $codeReview;
    public $vstsProject;

    public function __construct( CodeReview $codeReview, VstsProject $vstsProject )
    {
        $this -> codeReview = $codeReview;
        $this -> vstsProject = $vstsProject;
    }
}
