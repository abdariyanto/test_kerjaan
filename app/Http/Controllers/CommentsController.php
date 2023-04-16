<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessComment;
use App\Models\Comments;
use App\Models\LogNews;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    public function createComments(Request $request)
    {
        // $redis = Redis::connection();
        $cekNews = News::where('id', $request->input('news_id'))->get();
        if ($cekNews->count() < 1) {
            return response()->json([
                'errors' => 'News id not valid'
            ], 400);
        }
        // validate request data
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'user_id' => 'required|integer',
            'news_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $comments = [
            'user_id' => $request->input('user_id'),
            'news_id' => $request->input('news_id'),
            'content' => $request->input('content')
        ];
        Redis::hmset('mydata', $comments);
        ProcessComment::dispatch($comments);
      
        return response()->json([
            'message' => 'Comments added queue',
            'data' => $comments
        ], 201);
    }

    public function showComments(Request $request)
    {
        $id_news = $request->input('news_id');
        $cekNews = Comments::where('id', $request->input('comments_id'))->get();
        if ($cekNews->count() < 1) {
            return response()->json([
                'errors' => 'Comments id not valid'
            ], 400);
        }
        $getNews = DB::table('news')
        ->selectRaw("news.*,comments.content")
        ->join("comments", "comments.news_id", '=', "news.id")
        ->where("news.id", $cekNews[0]->news_id)->limit(1);
        $getNews->get();
        print_r($getNews);die;
        return response()->json([
            'message' => 'Comments get successfully',
            'data' => $getNews
        ], 201);
    }
}
