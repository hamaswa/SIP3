@extends('layouts.app')

@section('content')

    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box">
            <div class="box-body">
				@include('cms.case_management.table')
			</div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

@push('scripts')
<script>


</script>
@endpush

