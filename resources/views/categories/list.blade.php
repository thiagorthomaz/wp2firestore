@extends('adminlte::page')

@section('title', "Categories")

@section('content_header')
<h1>Categories</h1>
<h4>Choose which categories are going to be imported to Firestore</h4>
    
@stop

@section('content')
    
<style>

    table tr td {
        max-width: 200px;
        word-wrap:break-word;
    }


</style>

<button type="button" id="syncWithFirestore" class="btn btn-block btn-primary">Sync with Firestore</button>


<table class="table table-bordered">
    <tbody>
    <tr>
      <th style="width: 10px">Import</th>
      <th>Title</th>
    </tr>

    @foreach ($categories as $cat)
        <tr>
          <td> 
            @if ($cat->checked > 0)
              <input id="{{ $cat->term_id }}" name="{{ $cat->term_id }}" checked="1" class="categoryToImport" type="checkbox">  
            @else
              <input id="{{ $cat->term_id }}" name="{{ $cat->term_id }}" class="categoryToImport" type="checkbox">  
            @endif
          </td>
          <td> <label for="{{ $cat->term_taxonomy_id }}">
              <a href="{{ url('/wp/posts/list') }}/{{$cat->term_id}}">{{ $cat->name }}</a>
            </label> </td>
        </tr>
    @endforeach
    
  </tbody>
</table>

@stop



@section('js')
    <script> 
      
      $(".categoryToImport").change(function() {
        
        var _id = $(this).attr("id");
        console.log(_id);
        
        $.ajax({
          url: "import/"+ _id,
          context: document.body
        }).done(function() {
          //alert("Imported");
        });
        
        
      });
      
      $("#syncWithFirestore").click(function() {
        
        var _id = $(this).attr("id");
        console.log(_id);
        
        $.ajax({
          url: "syncWithFirestore",
          context: document.body
        }).done(function() {
          alert("Synced");
        });
        
        
      });
  
    </script>
@stop