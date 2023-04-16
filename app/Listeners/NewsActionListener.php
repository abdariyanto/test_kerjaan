<?php

namespace App\Listeners;

use App\Events\NewsAction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Log_news;


class NewsActionListener
{
   
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        
        Log_news::create([
            'news_id' =>  $event->news['news_id'],
            'user_id' => $event->news['user_id'],
            'action' =>  $event->action,
        ]);


    }
}
