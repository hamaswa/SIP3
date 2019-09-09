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
                    {{--<th>data</th>--}}
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
                        <td>{{ $sub_data->agent }}</td>
                    @if(strtolower($sub_data->verb)=="abandon" OR strtolower($sub_data->verb)=="exitwithtimeout")
                            <td>   Abandon </td>
                            {{--<td>{{ gmdate("H:i:s",$sub_data->data2) }}</td>--}}
                            {{--<td>00:00:00</td>--}}

                        @elseif(strtolower($sub_data->verb)=="connect")
                            <td>   Answered </td>
                            {{--<td>00:00:00</td>--}}
                            {{--<td>{{ gmdate("H:i:s",$sub_data->data2) }}</td>--}}
                        @endif
                        <td>{{ $sub_data->queue }}</td>
                        {{--<td>{{ $sub_data->data }}</td>--}}

                        {{--<td>{{ $sub_data->data3 }}</td>--}}
{{--                        <td>{{ $sub_data->data4 }}</td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>

        </td>

    </tr>
    @endif
