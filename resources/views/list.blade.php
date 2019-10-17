@extends('layouts.app')

@section('title')
    {!! $data[0] !!} 名单列表
@endsection

@section('content')
<div class="container">
<table class="table table-hover"> 
    <thead> 
        <tr> 
            <th>日期</th>
            <th>教室</th> 
            <th>时间</th> 
            <th>姓名</th>
            <th>状态</th>
            <th>候场教室操作</th>
            <th>面试教室操作</th>
            <th>候场教室操作</th>
            <th>信息</th>
        </tr> 
    </thead> 
    <tbody> 
        {!! $data[1] !!}
    </tbody> 
</table>
</div>
@endsection