@extends('layouts.app')

@section('title')
    {!! $data[0] !!} 名单列表
@endsection

@section('content')
<div class="container">
<table class="table table-hover"> 
    <thead> 
        <tr>  
            {!! $data[1] !!}
        </tr> 
    </thead> 
    <tbody> 
        {!! $data[2] !!}
    </tbody> 
</table>
</div>
@endsection