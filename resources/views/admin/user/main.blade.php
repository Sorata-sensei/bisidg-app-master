@extends('admin.template.index')

@push('css')
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ route('user.admin.create') }}" class="btn btn-primary btn-lg shadow">
            <i class="fas fa-plus-circle me-2"></i> Add New Lecturer
        </a>
    </div>
    <div class="row">
        @foreach ($users as $user)
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ route('admin.students.CheckStudentByLecturer', $user->id) }}" class="text-decoration-none">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold  text-uppercase mb-1">
                                        <b>{{ $user->name }}</b>

                                    </div>

                                </div>
                                <div class="col-auto">
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo Lecturer"
                                        class="img-fluid rounded-circle"
                                        style="max-height: 50px; max-width: 50px;  object-fit: cover; object-position: center;">
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
@endpush
