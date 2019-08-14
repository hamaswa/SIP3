@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Case Management</div>
                <div class="panel-body">


                    {!! Form::model($case_management,['route'=>['case_management.store',$case_management->id],'method'=>'patch']) !!}

                    @include("cms.case_management.case_management")

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
