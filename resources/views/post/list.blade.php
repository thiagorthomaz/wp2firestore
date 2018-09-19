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
  <button type="button" id="sync" class="btn btn-block btn-primary">Sync</button>
@endif

@if (count($posts) == 0)
<div class="callout callout-warning">
  <h4>Hey!!!</h4>

  <p><a href="{{ url('wp/categories/list') }}">Take a look if there's some category selected</a></p>
</div>
@endif

<table class="table table-bordered">
    <tbody>
    <tr>
      <th style="width: 10px">#</th>
      <th>Title</th>
      <th>Status</th>
      <th>Posted in</th>
      <th>Imported at</th>
      <th>Post last modified at</th> 
      <th></th> 
    </tr>



    @foreach ($posts as $post)
        <tr>
            <td> {{ $post->ID }} </td>
            <td> {{ $post->post_title }} </td>
            <td> {{ $post->post_status }} </td>
            <td> {{ $post->post_date }} </td>
            <td> {{ $post->created_at }} </td>
            <td> {{ $post->post_modified }} </td>
            <td> 
              @if ($post_type === "firestore")
              <button id="deleteFromFS" onclick="deletePost({{ $post->ID }})" class="btn btn-block btn-danger">Delete</button>
              @endif
            </td>
        </tr>
    @endforeach
    
  </tbody>
</table>



@stop


@section('js')
    <script> 
      
      $("#sync").click(function() {
        
        $.ajax({
          url: "sync",
          context: document.body
        }).done(function() {
          alert("Synced");
        });
        
      });
      
      function deletePost(_post_id_) {
        
        $.ajax({
          url: "delete/"+_post_id_,
          context: document.body
        }).done(function() {
          alert("Deleted");
          location.reload();
        });
        
      }
      
    </script>
@stop