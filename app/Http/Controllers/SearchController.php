<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class SearchController extends Controller
{
    public function search(Request $request) {
    	$content = $request->content;

    	$topic = empty($content) ? '请输入内容' : Topic::where('body','like', "%{$content}%")->get();
    	
    	$content = json_encode($topic);
    	echo $content;
    }
}
