@extends('mage2::admin.master')
 
@section('content')
<div class="content">
	<h1>Entity Add Form</h1>

    <div class="col-md-5">
            {!! Form::open(array('files' => 'true', 'url' => url('admin/entity'))) !!}
        @include('mage2::admin.entity._edit')
            {!! Form::close() !!}
	</div>
</div>
@endsection