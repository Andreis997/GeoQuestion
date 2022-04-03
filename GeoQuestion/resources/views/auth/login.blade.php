@extends('app')
@section('content')
    <div id="logo"
         style="background-image: url('img/logo.svg');background-repeat: no-repeat; height: 79px; position: absolute; width: 304px; margin-top: 60px; margin-left: 622px;"></div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card" style="top: 50%; background: #ffd053; border-radius: 25px;">
                <h3 class="card-header text-center">Login</h3>
                <div class="card-body">
                    <form method="POST" action="{{ route('login.custom') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="text" placeholder="Email" id="email" class="form-control" name="email" required
                                   autofocus>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <input type="password" placeholder="Password" id="password" class="form-control"
                                   name="password" required>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-3">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="d-grid" style="margin-bottom: 3px">
                            <a class="btn btn-dark btn-block" style="background: #ff7e02; border-radius: 25px;" href="{{ route('register-user') }}">Register</a>
                        </div>
                        <div class="d-grid ">
                            <button type="submit" class="btn btn-dark btn-block" style="background: #000000; border-radius: 25px;">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
