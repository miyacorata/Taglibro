@extends('errors::minimal')

@section('title', 'Forbidden')
@section('code', '403')
@section('message', $exception->getMessage() ?: '閲覧する権限がありません')
