<tr class="sub_tr">
<td colspan="7"  class="box" style="border: solid 1px #aaaaaa;">
        <table class="col-lg-12 table table-responsive subtable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Caller ID</th>
                <th>From</th>
                <th>To</th>
                {{--<th>Direction</th>--}}
                <th>Ring Time</th>
                <th>Bill Sec</th>
                <th>Recording</th>
                <th>Status</th>
            </tr>
        </thead>
            <tbody>
            @foreach($iReportDetail as $data)
                <tr>
                    <td>{{ $data->calldate }}</td>
                    <td>{{ $data->did }}</td>
                    <td>{{ $data->src }}</td>
                    <td>{{ $data->destination }}</td>
                    <td>{{ $data->ringtime }}</td>
                    <td>{{ $data->billsec }}</td>
                    <td>
                        @if($data->billsec!=0)
                            <a href="{{ asset("/") }}download.php?id={{ urlencode($data->Recording) }}">{{ $data->Recording }}</a>
                        @endif
                    </td>

                    <td>{{ $data->disposition }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </td>
</tr>