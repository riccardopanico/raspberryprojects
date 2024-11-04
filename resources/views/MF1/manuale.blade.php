@extends('MF1.index')
@section('main')
    <iframe src="{{ asset('pdf/manuale_uso.pdf') }}#toolbar=0" class="full-screen-iframe"></iframe>
    <style>
        .content-wrapper,
        .content,
        .container-fluid {
            padding: 0 !important;
        }
    </style>
@endsection
