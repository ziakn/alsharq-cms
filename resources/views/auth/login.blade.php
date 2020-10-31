@extends('layouts.app')

@section('content')

        <form class="py-5 " method="POST" action="{{ route('login') }}">
        @csrf
                <div>
                    <!-- <img class="d-flex logo " src="logo.png" alt="SimlpistQ" width="230px"> -->
                </div>
                <div class="container align-content-center mx-auto">
                <h4 class="py-4 px-4" style="text-align: center">Login</h4>
                </div>
                <div class="form-group">
                <input id="email"  placeholder="Email" type="email" class=" form-rounded bg-light py-4 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
        
                </div>
                <div class="form-group">
                <input id="password" placeholder="Password" type="password" class="form-control form-rounded py-4 bg-light @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <br><div class="form-group form-check align-content-center chkbox">
                  <input type="checkbox" class="form-check-input"name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="exampleCheck1">Remember Me</label>
                </div>

                <div class="d-flex">
                  <button type="submit" class="btn sub mx-auto py-2 shadow-lg">Login</button>
                </div>
                <div class="d-flex">
                <!--@if (Route::has('password.request'))-->
                <!--                    <a class="btn btn-link mx-auto py-2"  href="{{ route('password.request') }}">-->
                <!--                        {{ __('Forgot Your Password?') }}-->
                <!--                    </a>-->
                <!--                @endif-->
                </div>
            </form>


@endsection
