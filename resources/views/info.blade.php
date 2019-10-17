@extends('layouts.app')

@section('title')
    {!! $data[0] !!} 详情页面
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="card-header">面试官评论</div>
            <div class="container">
                <table class="table table-sm table-hover">
                    <thead>
                        <th style='text-align:left'>填写时间</th>
                        <th style='text-align:left'>面试官</th>
                        <th style='text-align:left'>评论</th>
                        <!--
                            <th>打分习惯-平均分</th>
                            <th>方差</th>
                        -->
                    </thead>
                    <tbody>
                        {!! $data[1] !!}
                    </tbody>
                </table>
            </div>
            <br/>
            <div class="card-header">个人资料</div>
            <div class="container">
                <table class="table table-sm table-hover">
                    <tbody>
                        {!! $data[2] !!}
                    </tbody>
                </table>
            </div>

            {{ Form::open(array('url' => 'insert_data')) }}
            {{Form::label('d', '注：请在此人面试结束完成后再提交评论。', ['class' => 'alert alert-info'])}}
            <br>
            {{Form::label('cmt', '评论', ['class' => ''])}}
            {{Form::textarea('cmt')}}
            <hr>
            {{Form::radio('id', $data[3], true)}}
            {{Form::submit('提交', ['class' => 'btn btn-primary my_submit'])}}
            {{Form::close()}}
            <br/>
        </div>
    </div>
</div>
@endsection