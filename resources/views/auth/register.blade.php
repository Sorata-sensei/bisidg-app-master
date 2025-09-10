@extends('auth.template.index')
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">Registration</h2>
                        <form method="POST" action="{{ route('register.submit') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    autofocus class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" required class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password:</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
