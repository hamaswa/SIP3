@if(($type=="queue") OR ($type=="month") OR ($type=="week") OR $type=='day' OR $type=="hour" OR $type=="dayweek")
   @if(request()->user()->can("download_distribution"))
    <tr class="sub_tr">
        <td colspan="6" id="queue_sub" class="box right-side">
            <form method="post" action="{{ route("dist_export") }}">
            @foreach($inputs as $k => $v)
                <input type="hidden" name="{{ $k }}" value="{{ $v }}" >
            @endforeach
                <input type="submit" class="btn btn-default" name="submit" value="Download Report">
            </form>
        </td>
    </tr>
    @endif
    <tr class="sub_tr">
        <td colspan="6" id="queue_sub" class="box"  style="border: solid 1px #aaaaaa;">
            <table class="col-lg-12 subdata table dataTable">
                <thead>
                <tr>
                    <th>Caller ID</th>
                    <th>Date</th>
                    <th>Agent</th>
                    <th>Status</th>
                    <th>Queue</th>
                    @if(request()->user()->can("download_queue_recording"))
                    <th>Recording</th>
                    @endif

                    {{--<th>Wait Time(data1)</th>--}}
                    {{--<th>Call Time(data2)</th>--}}
                    {{--<th>data3</th>--}}
                    {{--<th>data4</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($data as $sub_data)

                    <tr{{ ((strtolower($sub_data->verb)=="abandon" OR strtolower($sub_data->verb)=="exitwithtimeout")?" class=bg-red": " class=bg-green") }}>
                        <td>{{ $sub_data->caller_id }}</td>
                        <td>{{ $sub_data->date }}</td>
                        <td>{{ (isset($sub_data->agent_name) and $sub_data->agent_name!="")?$sub_data->agent_name:"NONE" }}</td>
                    @if(strtolower($sub_data->verb)=="abandon")
                        <td>   Abandon </td>
                        {{--<td>{{ gmdate("H:i:s",$sub_data->data2) }}</td>--}}
                        {{--<td>00:00:00</td>--}}

                    @elseif(strtolower($sub_data->verb)=="exitwithtimeout")
                            <td>   Exitwithtimeout </td>

                        @elseif(strtolower($sub_data->verb)=="connect")
                        <td>   Answered </td>
                        {{--<td>00:00:00</td>--}}
                        {{--<td>{{ gmdate("H:i:s",$sub_data->data2) }}</td>--}}
                    @endif
                    <td>{{ $sub_data->queue }}</td>

                    @if(request()->user()->can("download_queue_recording"))
                            <td>
                                @if($sub_data->recordingfile!="")
                                <a href="{{ asset("/") }}download.php?id={{ $sub_data->recordingfile }}">
                                <i class ="fa fa-file-audio-o"></i> Recording </a>
                                @else
                                No Recording Found
                                @endif
                            </td>
                    @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

        </td>

    </tr>
    @endif
