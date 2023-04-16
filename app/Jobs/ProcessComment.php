<?php

namespace App\Jobs;

use App\Events\NewComment;
use App\Models\Comments;
use App\Models\Log_news;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ProcessComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $comment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $commentsKey = 'comments';
        $commentIdKey = 'comment_id';

        // Generate new comment ID
        // $commentId = Redis::incr($commentIdKey);

        $data = Redis::hgetall('mydata');

        $model = new Comments();
        $model->user_id = $data['user_id'];
        $model->news_id = $data['news_id'];
        $model->content = $data['content'];
        $model->save();
        
        // // Create comment hash
        // $comment = [
        //     'id' => $commentId,
        //     'user_id' => $this->comment['user_id'],
        //     'news_id' => $this->comment['news_id'],
        //     'content' => $this->comment['content'],
        //     'created_at' => now()->toDateTimeString(),
        //     'updated_at' => now()->toDateTimeString()
        // ];

        // // Add the new comment to the list of comments
        // Redis::hset($commentsKey, $commentId, json_encode($comment));

        // DB::table('log_news')->insert([
        //     'news_id' => $this->comment['news_id'],
        //     'user_id' => $this->comment['user_id'],
        //     'content' => $this->comment['content'],
        //     'created_at' => now()->toDateTimeString(),
        //     'updated_at' => now()->toDateTimeString()
        // ]);

        // Log the comment
        // Log::info('New comment added to news #' . $this->comment['news_id'] . ' by user #' . $this->comment['user_id']);

        // Remove the comment from the queue
        // Redis::lrem('mydata', 1, json_encode($this->comment));
    }
}
