<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class FSPostController extends Controller
{

    public static function show()
    {
        $posts = DB::select('select * from wp_posts where post_type="post"');
        return view('posts', ['posts' => $posts]);
    }
}