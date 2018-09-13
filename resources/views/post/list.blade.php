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


@if ($post_type === "wordpress")
  <button type="button" id="importFromWP" class="btn btn-block btn-primary">Import</button>
@endif


<table class="table table-bordered">
    <tbody>
    <tr>
      <th style="width: 10px">#</th>
      <th>Title</th>
      <th>Status</th>
      <th>Imported at</th>
      <th>Post last modified at</th> 
    </tr>



    @foreach ($posts as $post)
        <tr>
            <td> {{ $post->ID }} </td>
            <td> {{ $post->post_title }} </td>
            <td> {{ $post->post_status }} </td>
            <td> {{ $post->created_at }} </td>
            <td> {{ $post->post_modified }} </td>
        </tr>
    @endforeach
    
  </tbody>
</table>



@stop


@section('js')
    <script> 
      
      $("#importFromWP").click(function() {
        
        $.ajax({
          url: "import",
          context: document.body
        }).done(function() {
          alert("Imported");
        });
        
        
      });
  
    </script>
@stop