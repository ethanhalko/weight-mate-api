@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">Change Password</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.set', Auth::user()) }}">
                            @csrf
                            <div class="form-group row">
                                <label for="old_password"
                                       class="col-sm-4 col-form-label text-md-right">{{ __('Old Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password"
                                           value="{{ old('password') }}"
                                           required
                                           autofocus>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="new_password"
                                       class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password"
                                           type="password"
                                           class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}"
                                           name="new_password"
                                           required>

                                    @if ($errors->has('new_password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="new_password_confirm"
                                       class="col-md-4 col-form-label text-md-right">{{ __('New Password (Confirm)') }}</label>

                                <div class="col-md-6">
                                    <input id="new_password_confirm"
                                           type="password"
                                           class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}"
                                           name="new_password_confirm"
                                           required>

                                    @if ($errors->has('new_password_confirm'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password_confirm') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
