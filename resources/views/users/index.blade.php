@extends('layouts.velzon')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Manajemen Pengguna</h4>
            <div class="page-title-right">
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                    <i class="ri-add-line align-bottom me-1"></i> Tambah Pengguna
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Cabang</th>
                                <th scope="col" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'superadmin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @else
                                        <span class="badge bg-primary">Admin</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Super admin doesn't need to be locked to a single branch unless specified -->
                                    {{ $user->branch ? $user->branch->name : 'Semua Cabang (Pusat)' }}
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-soft-info">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada pengguna terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
