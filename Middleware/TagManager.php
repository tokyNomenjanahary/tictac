<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;



use Closure;

class TagManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$this->saveTags($request);
        return $next($request);
    }

    private function saveTags($request)
    {
    	if(isset($request->utm_source) || isset($request->utm_campaign) || isset($request->utm_content) || isset($request->utm_medium) || isset($request->utm_term)) 
    	{
    		$ip = get_ip();
	    	$data = array(
	    		"ip" => $ip,
	    		"utm_source" => (isset($request->utm_source)) ? $request->utm_source : NULL,
	    		"utm_medium" => (isset($request->utm_medium)) ? $request->utm_medium : NULL,
	    		"utm_campaign" => (isset($request->utm_campaign)) ? $request->utm_campaign : NULL,
	    		"utm_term" => (isset($request->utm_term)) ? $request->utm_term : NULL,
	    		"utm_content" => (isset($request->utm_content)) ? $request->utm_content : NULL,
	    		"date" => date('Y-m-d H:i:s')
	    	);
	    	DB::table("tags")->insert($data);
    	}
    	
    }
	
}
