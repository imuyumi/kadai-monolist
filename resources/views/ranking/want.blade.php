@extends('layouts.app')
@section('content')
    <h1>wantランキング</h1>
    @include('items.items',['items'=>$items])
@endsection