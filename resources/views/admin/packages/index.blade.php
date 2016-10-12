@extends('layouts.app')

@section('content')
  <div class="container">
        <h1 class="pull-left">Packages</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('packages.create') !!}">Add New</a>

        <div class="clearfix"></div>

        <div class="clearfix"></div>
        
        @include('admin.packages.table')
  </div>
@endsection