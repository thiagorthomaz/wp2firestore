@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Admin</h1>
@stop

@section('content')
    

<table class="table table-bordered">
    <tbody>
    
    @foreach ($wp_options as $opt)
        <tr>
          <td style="width: 200px;">
            @if($opt->option_name == 'admin_email')
              Admin e-mail
            @endif
            
            @if($opt->option_name == 'blogdescription')
              Blog description
            @endif
            
            @if($opt->option_name == 'blogname')
              Blog name
            @endif
            
            @if($opt->option_name == 'home')
              Home page
            @endif
            
            @if($opt->option_name == 'siteurl')
              Site url
            @endif
            
          </td>
          <td> {{ $opt->option_value }} </td>
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