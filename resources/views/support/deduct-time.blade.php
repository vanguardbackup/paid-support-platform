@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">User Support Time Management</h1>

        <form action="{{ route('support.deduct.list') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ $search }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Search</button>
                            @if ($search)
                                <a href="{{ route('support.deduct.list') }}" class="btn btn-secondary">Clear</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <select name="balance_filter" class="form-control" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        <option value="with_balance" {{ $balanceFilter === 'with_balance' ? 'selected' : '' }}>Users with Balance</option>
                        <option value="without_balance" {{ $balanceFilter === 'without_balance' ? 'selected' : '' }}>Users without Balance</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Support Time Balance</th>
                    <th>Deduct Time</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <img src="{{ $user->gravatar }}" alt="{{ $user->name }}" class="rounded-circle mr-2" width="32" height="32">
                            {{ $user->name }}
                        </td>
                        <td>{{ $user->email }}</td>
                        <td class="support-time-balance">{{ $user->support_time_balance }} hours</td>
                        <td>
                            @if ($user->support_time_balance > 0)
                                <form action="{{ route('support.deduct-time.post') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <input type="number" name="time" class="form-control form-control-sm mr-2" min="1" max="{{ $user->support_time_balance }}" value="0" required style="width: 80px;">
                                    <button type="submit" class="btn btn-vanguard-secondary btn-sm">Deduct</button>
                                </form>
                            @else
                                <span class="text-muted">No balance</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
@endsection
