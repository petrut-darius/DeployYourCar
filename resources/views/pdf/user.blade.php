@php
    use Carbon\Carbon;
    $now = Carbon::now();
@endphp
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
    </style>
</head>
<body>

<h1>User info</h1>
<h2>Current date: {{$now}}</h2>

<p><b>ID:</b> {{ $user->id }}</p>
<p><b>Name:</b> {{ $user->name }}</p>
<p><b>Email:</b> {{ $user->email }}</p>

@if($permissions->isNotEmpty())
    <h3>Permissions</h3>
    <ul>
        @foreach ($permissions as $permission)
            <li><b>Permission name: </b>{{ $permission->name }}   <b>Permission description: </b>{{ $permission->description}}</li>
        @endforeach
    </ul>
@endif

@if($user->groups->count())
    <h3>Groups</h3>
    <ul>
        @foreach ($user->groups as $group)
            <li>{{ $group->name }}</li>
        @endforeach
    </ul>
@endif
</body>
</html>
