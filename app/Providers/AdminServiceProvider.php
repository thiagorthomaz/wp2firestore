<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;


/**
 * Description of AdminServiceProvider
 *
 * @author thiago
 */
class AdminServiceProvider {
  
  public function loadWPOptions() {
    return DB::select('SELECT * FROM wp2firestore.wp_options where option_name in ("siteurl", "home", "blogname", "blogdescription", "admin_email")');
  }
  
  
  
}
