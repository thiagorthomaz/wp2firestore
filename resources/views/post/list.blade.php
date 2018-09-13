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


@if ($post_type === "wordpress")
<div class="post-categories">
    @foreach ($categories as $cat)
      <div class="checkout">
        <input name="{{ $cat->term_taxonomy_id }}" type="checkbox"> {{ $cat->name }}
      </div>
      
    @endforeach
</div>
@endif

<table class="table table-bordered">
    <tbody>
    <tr>
      <th style="width: 10px">#</th>
      <th>Title</th>
      <th>Status</th>
      <th>Imported</th>
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
  <style>
    .post-categories {
      display: flex;
      flex-direction: row;
      width: 100%;
      flex-wrap: wrap;
    }
    
    .checkout {
      display: flex;
      flex-direction: row;
      margin: 5px;
    }
    
  </style>
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