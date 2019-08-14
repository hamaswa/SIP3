@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-table-row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Case Management</div>
                    <div class="panel-body">
                        <table>

                        <tr>
                               <th>
                                   Subject
                               </th>
                            <td>:</td>
                            <th>{{$case->subject}}
                            </th>
                        </tr>
                        <tr>
                            <th class="d-table-cell">
                                Name
                            </th> <td>:</td> <td class="d-table-cell">
                                {{$case->name}}
                            </td>
                        </tr><tr>
                            <th class="d-table-cell">
                                Contact
                            </th>  <td>:</td><td class="d-table-cell">
                                {{$case->contact_no}}
                            </td>
                        </tr><tr>
                            <th class="d-table-cell">
                                Taxi No
                            </th> <td>:</td> <td class="d-table-cell">
                                {{$case->taxi_no}}
                            </td>
                        </tr><tr>
                            <th class="d-table-cell">
                                Incident Location
                            </th>  <td>:</td><td class="d-table-cell">
                                {{$case->incident_location}}
                            </td>
                        </tr><tr>
                            <th class="d-table-cell">
                                Pickup Point A
                            </th> <td>:</td> <td class="d-table-cell">
                                {{$case->pickup_point_a}}
                            </td>
                        </tr><tr>
                            <th class="d-table-cell">
                                Pickup Point B
                            </th> <td>:</td> <td class="d-table-cell">
                                {{$case->pickup_point_b}}
                            </td>
                        </tr><tr>
                            <th class="d-table-cell">
                                Case Type
                            </th>  <td>:</td><td class="d-table-cell">
                                {{$case->case_type}}
                            </td>
                        </tr><tr>
                            <th class="d-table-cell">
                                Status
                            </th>  <td>:</td><td class="d-table-cell">
                                {{$case->case_status}}
                            </td>
                        </tr>
                        </table>

                        <div class=" col-lg-12">
                            @foreach($case->Comments as $c)
                               <div class="col-lg-12">
                                  <p> {{$c->comment}}</p>
                               </div>
                            @endforeach
                        </div>

                        @if($case->case_status=="open")

                        {!! Form::open(['route'=>'case_comments.store']) !!}

                        @include("cms.case_management.case_comments.case_comments_form");

                        {!! Form::close() !!}

                        @endif

                     </div>
                </div>
            </div>
        </div>
    </div>
@endsection
