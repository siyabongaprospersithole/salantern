@extends('layouts.app')

@section('title')
SA Lantern | Signup 
@endsection

@section('form')
<div class="form-body " id="app">
        <div class="website-logo">
            <a href="index.html">
                <div class="logo">
                    <img class="logo-size" src="https://www.salantern.co.za/images/SA%20Lantern%20Logo%20dark%20background.png" alt="">
                </div>
            </a>
        </div>
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">

                </div>
            </div>
<signup-component> </signup-component>
</div>
@endsection