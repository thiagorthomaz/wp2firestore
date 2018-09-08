<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Google\Cloud\Firestore\FirestoreClient;


class FSPostController extends Controller
{

    public static function show()
    {

        $firestore = new FirestoreClient();

        $collectionReference = $firestore->collection('noticias');
        
        $documents = $collectionReference->documents();


        foreach($documents as $document) {

            if ($document->exists()) {
                print_r($document['data']);
            }

        }

        //print_r($documents);

        exit;
        $posts = DB::select('select * from wp_posts where post_type="post"');
        return view('posts', ['posts' => $posts]);
    }
}