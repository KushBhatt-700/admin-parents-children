@extends('layouts.app')

@section('content')
<div class="container">
  <h3>Add Child</h3>

  <form method="post" enctype="multipart/form-data" action="{{ route('children.store') }}">
    @csrf

    <div class="row">

      <div class="col-md-6 mb-3">
        <label>First Name</label>
        <input type="text" name="first_name" class="form-control">
      </div>

      <div class="col-md-6 mb-3">
        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control">
      </div>

      <div class="col-md-6 mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
      </div>

      {{-- NEW Country --}}
      <div class="col-md-6 mb-3">
        <label>Country</label>
        <select name="country_id" id="country" class="form-control">
          <option value="">Select</option>
          @foreach($countries as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      {{-- NEW State --}}
      <div class="col-md-6 mb-3">
        <label>State</label>
        <select name="state_id" id="state" class="form-control">
          <option value="">Select</option>
        </select>
      </div>

      {{-- NEW City --}}
      <div class="col-md-6 mb-3">
        <label>City</label>
        <select name="city_id" id="city" class="form-control">
          <option value="">Select</option>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label>Birth Date</label>
        <input type="date" name="birth_date" id="birth_date_child" class="form-control">
      </div>

      <div class="col-md-6 mb-3">
        <label>Age</label>
        <input name="age" id="age_child" class="form-control" readonly>
      </div>

      <div class="col-md-12 mb-3">
        <label>Parents (select multiple)</label>
        <select name="parents[]" multiple class="form-control">
          @foreach($parents as $p)
            <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
          @endforeach
        </select>
      </div>

    </div>

    <button class="btn btn-primary">Save</button>
  </form>
</div>

{{-- VERY SIMPLE SCRIPT --}}
<script>
  // AGE CALCULATION
  document.querySelector('#birth_date_child').addEventListener('change', function () {
    let b = new Date(this.value);
    if (!isNaN(b)) {
      let diff = Date.now() - b.getTime();
      let age = new Date(diff).getUTCFullYear() - 1970;
      document.querySelector('#age_child').value = age;
    }
  });

  // LOAD STATES
  document.querySelector('#country').addEventListener('change', function () {
    let id = this.value;
    fetch('/get-states/' + id)
      .then(res => res.text())
      .then(data => {
        document.querySelector('#state').innerHTML = data;
        document.querySelector('#city').innerHTML = '<option value="">Select</option>';
      });
  });

  // LOAD CITIES
  document.querySelector('#state').addEventListener('change', function () {
    let id = this.value;
    fetch('/get-cities/' + id)
      .then(res => res.text())
      .then(data => {
        document.querySelector('#city').innerHTML = data;
      });
  });
</script>
@endsection
