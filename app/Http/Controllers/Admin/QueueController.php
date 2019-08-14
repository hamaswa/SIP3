<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Queue;

class QueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function getQueue(Request $request)
    {
        $data = Queue::where('user_id', "=" ,$request->user_id)->Get();
        $html = '<table style="width:100%">';
        $html .= '<tr><th>Queue</th><th>Description</th><th>Action</th></tr>';
        foreach($data as $queue)
        {
            $html .= '<tr><td>'.$queue->queue.'</td><td>'.$queue->queue_description.'</td>
                    <td><a data-remote="'.$queue->id.'" id="deleteQueue" class="deleteQueue btn btn-default btn-xs">
        <i class="glyphicon glyphicon-trash"></i>
    </a></td></tr>';
        }
        $html .= '</table>';
        return $html;
    }

    public function addQueue(Request $request)
    {
        $input = $request->all();
        $queue = Queue::create($input);
    }

    public function deleteQueue(Request $request)
    {
        $queue = Queue::find($request->id);
        $queue->delete();
    }
}
