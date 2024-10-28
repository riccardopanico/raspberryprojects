@extends('template_1.index')
@section('main')
    <iframe src="{{ asset('pdf/manuale_uso.pdf') }}#toolbar=0" class="full-screen-iframe"></iframe>
@endsection
