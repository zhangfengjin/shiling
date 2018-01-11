@extends('layouts.app')@section('content')

    <!-- iCheck radio或checkbox-->
    <link href="{{url('css/plugins/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <!-- Datatables -->
    <link href="{{url('css/plugins/datatables-bs/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('css/plugins/datatables-fixedheader-bs/fixedHeader.bootstrap.min.css')}}"
          rel="stylesheet"> <!--固定列头 漂浮形式-->
    <!-- iCheck iCheck radio或checkbox-->
    <script src="{{url('js/plugins/iCheck/icheck.min.js')}}"></script>
    <!-- Datatables -->
    <script src="{{url('js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('js/plugins/datatables-bs/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('js/plugins/datatables-fixedheader-bs/dataTables.fixedHeader.js')}}"></script><!--固定列头  漂浮形式-->
    <script src="{{url('js/tablelist.js')}}"></script>
    @yield("tablecontent")
@endsection

