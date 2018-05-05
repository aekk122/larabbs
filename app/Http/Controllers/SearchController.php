<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use DB;

class SearchController extends Controller
{
    public function search(Request $request) {
    	$content = $request->content;

    	$topic = empty($content) ? '请输入内容' : DB::select("select * from topics where title like '%$content%'or body like '%$content%' ");
    	$content = json_encode($topic, true);
    	echo $content;
    }
}
