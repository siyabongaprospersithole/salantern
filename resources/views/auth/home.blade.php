@extends('layouts.app')


@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/auth.css') }}">
@endsection


@section('title')
SA Lantern | Home 
@endsection


@section('form')
<div class="form-body on-top" id="app">
        <div class="website-logo">
            <a href="index.html">
                <div class="logo">
                    <img class="logo-size" src="https://www.salantern.co.za/images/SA%20Lantern%20Logo%20dark%20background.png" alt="">
                </div>
            </a>
        </div>
      <profile-settings-component :user="{{ auth()->user() }}"> </profile-settings-component>
      <change-password-component :user="{{ auth()->user() }}"> </change-password-component>
    </div>
@endsection

@section('scripts')
@endsection