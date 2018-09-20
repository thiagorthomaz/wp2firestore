<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\PostServiceProvider;
use App\Providers\TaxonomyServiceprovider;

class WPPostController extends Controller
{

    public static function show($categoryId = null)
    {
      
      $post_provider = new PostServiceProvider();
      $taxonomy_provider = new TaxonomyServiceprovider();
      
      if (is_null($categoryId)) {
        $posts = $post_provider->loadFromWordPress();  
      } else {
        $posts = $post_provider->loadFromWordPressByCategory($categoryId);        
      }
      
      $categories = $taxonomy_provider->loadCategoriesFromWP();
      
      return view(
          'post/list', 
          [
              'posts' => $posts,
              'categories' => $categories,
              'post_type' => 'wordpress',
              'title' => 'Posts Wordpress',
              'categoryId' => $categoryId
          ]

      );
    }
    
    public static function sync($categoryId = null)
    {
      $post_provider = new PostServiceProvider();
      if (is_null($categoryId)) {
        $post_provider->syncWithFirestore();  
      } else {
        $post_provider->syncWithFirestoreByCategory($categoryId);
      }
      
      echo json_encode(array("response" => "Synced"));
    }
    
}