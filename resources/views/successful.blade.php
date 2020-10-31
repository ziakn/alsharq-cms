@extends('layouts.app')

@section('content')

        <form class="py-5 " method="POST" action="{{ route('login') }}">
        @csrf
                <div>
                    <!-- <img class="d-flex logo " src="logo.png" alt="SimlpistQ" width="230px"> -->
                </div>
                <div class="container align-content-center mx-auto">
                <h4 class="py-4 px-4" style="text-align: center">Password changed successfully</h4>
                </div>
            </form>
@endsection
