@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif


                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <div class="panel-heading">Case Management</div>
                <div class="panel-body">


                    {!! Form::open(['route'=>'case_comments.store']) !!}

                    @include("cms.case_management.case_comments.case_comments_form");

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
