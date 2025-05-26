@extends('errors::minimal')

@section('title', 'Internal Server Error')
@section('code', '500')
@section('message', $exception->getMessage() ?: 'サーバー内部でエラーが発生しました')
