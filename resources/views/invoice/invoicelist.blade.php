
@extends('layouts.app')
@section('title', "Invoice -" . Auth::user()->user_type)
@section('content')
<invoice-list :houseList="{{json_encode($houseList)}}" :invoiceslist="{{json_encode($invoicesList)}}" :allsum="{{json_encode($allsum)}}"></invoice-list>
{{-- <invoice-list ></invoice-list> --}}
@endsection
