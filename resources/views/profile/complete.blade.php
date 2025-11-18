@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Complete your profile</h3>
  <form method="post" enctype="multipart/form-data" action="{{ route('profile.complete.store') }}">
    @csrf
    <div class="mb-3"><label>First name</label><input name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}">@error('first_name')<div class="text-danger">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label>Last name</label><input name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">@error('last_name')<div class="text-danger">{{ $message }}</div>@enderror</div>
    <div class="mb-3"><label>Profile image</label><input type="file" name="profile_image" class="form-control">@error('profile_image')<div class="text-danger">{{ $message }}</div>@enderror</div>
    <button class="btn btn-primary">Complete</button>
  </form>
</div>
@endsection
