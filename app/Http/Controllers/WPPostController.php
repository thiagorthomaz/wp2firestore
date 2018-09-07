<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class WPPostController extends Controller
{

    public static function show()
    {
        $posts = DB::select('select * from wp_posts where post_type="post"');
        return view(
            'post/list', 
            [
                'posts' => $posts,
                'title' => 'Posts Wordpress'
            ]
            
        );
    }
}