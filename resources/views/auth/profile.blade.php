@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <h1><strong>Profile</strong></h1>

@stop

@section('content')
    <div class="row justify-content-left">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">Name</th>
                                <td>{{ Auth::user()->username }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ Auth::user()->role ?? 'User' }}</td>
                            </tr>
                            <tr>
                                <th>Registered At</th>
                                <td>{{ Auth::user()->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Last Login</th>
                                <td>{{ Auth::user()->last_login_at ?? 'Never' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/custom.css">
@stop

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const logoutBtn = document.getElementById('logout-button');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            }
        });
    </script>
@stop
