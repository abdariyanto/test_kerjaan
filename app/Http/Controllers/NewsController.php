<?php

namespace App\Http\Controllers;

use App\Events\NewsAction;
use App\Models\Log_news;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class NewsController extends Controller
{
  
    public function store(Request $request)
    {

        // check if user is admin
        if ($request->input('role') != 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4080'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        // upload image
        $image_name = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('public/news', $image_name);

        // save news data to database
        $news = News::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => '/storage/news/' . $image_name
        ]);

        $log = [
            'news_id' => $news->id,
            'user_id' => $request->all('user_id')['user_id'],
        ];
        event(new NewsAction($log, 'create'));

        return response()->json([
            'message' => 'News created successfully',
            'data' => $news
        ], 201);
    }

    public function update_news(Request $request)
    {

        // check if user is admin
        if ($request->input('role') != 'admin' || $request->input('news_id') == '') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'news_id' => 'required|integer',
            'description' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:4080'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $cek = News::where('id',$request->input('news_id'))->get();
        if($cek->count() < 1){
            return response()->json([
                'errors' => 'News id not valid'
            ], 400);
        }
        if ($request->file('image')) {
            // upload image
            $image_name = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/news', $image_name);
            // save news data to database
            $news = News::where('id', $request->input('news_id'))->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => '/storage/news/' . $image_name
            ]);
        } else {
            // save news data to database
            $news = News::where('id', $request->input('news_id'))->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);
        }

        // log news creation
        $log_data = Log_news::create([
            'news_id' => $request->input('news_id'),
            'user_id' => $request->input('user_id'),
            'event' => 'update',
            'created_at' => now()
        ]);

        $log = [
            'news_id' => $request->input('news_id'),
            'user_id' => $request->input('user_id'),
        ];
        event(new NewsAction($log, 'create'));

        return response()->json([
            'message' => 'News created successfully',
            'data' => $news
        ], 201);
    }

    public function delete_news(Request $request)
    {

        // check if user is admin
        if ($request->input('role') != 'admin' || $request->all('news_id')['news_id'] == '') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // validate request data
        $validator = Validator::make($request->all(), [
            'news_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        $cek = News::where('id',$request->input('news_id'))->get();
        if($cek->count() < 1){
            return response()->json([
                'errors' => 'News id not valid'
            ], 400);
        }
        // save news data to database
        $news = News::where('id', $request->input('news_id'))->delete();

        // log news creation

        $log_data = log_news::create([
            'news_id' => $request->input('news_id'),
            'user_id' => $request->input('user_id'),
            'event' => 'delete',
            'created_at' => now()
        ]);
        // Redis::lpush('log_news', json_encode($log_data));

        return response()->json([
            'message' => 'News deleted successfully',
            'data' => $news
        ], 201);
    }

    public function show_news(Request $request)
    {

        // check if user is admin
        if ($request->input('role') != 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // validate request data
        $validator = Validator::make($request->all(), [
            'news_id' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }
        // show news data to database

        $perPage = $request->query('perPage') ?? 10;
        $news = News::paginate($perPage);
        // if ($request->input('news_id')) {
        //     $news = News::where('id', $request->input('news_id'))->get();
        // } else {
        //     $news = News::get();
        // }
        $success = [ 'data' => $news];
        // return $this->sendResponse($success, 'Show data successfully.');
        return response()->json([
            'message' => 'show successfully',
            'data' => $news
        ], 201);
    }


}
