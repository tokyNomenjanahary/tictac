<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;



class Performance
{
    public function allow_ip()
    {
        $keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        
		foreach ($keys as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, "127.0.0.1") !== false) {
						return $ip;
					}
				}
			}
		}

    }
}
