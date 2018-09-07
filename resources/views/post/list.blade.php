@extends('adminlte::page')

@section('title', $title)

@section('content_header')
    <h1>{{ $title }}</h1>
@stop

@section('content')
    
<style>

    table tr td {
        max-width: 200px;
        word-wrap:break-word;
    }


</style>

<table class="table table-bordered">
    <tbody>
    <tr>
      <th style="width: 10px">#</th>
      <th>Title</th>
      <th>Status</th>
      <th>imported</th>
      <th>Modified</th> 
    </tr>



    @foreach ($posts as $post)
        <tr>
            <td> {{ $post->ID }} </td>
            <td> {{ $post->post_title }} </td>
            <td> {{ $post->post_status }} </td>
            <td> Y/N </td>
            <td> {{ $post->post_modified }} </td>
        </tr>
    @endforeach
    
  </tbody>
</table>



@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop