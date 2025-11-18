@extends('layouts.app')

@section('content')
<div class="container">
  <h3>Add Parent</h3>

  <form method="post" enctype="multipart/form-data" action="{{ route('parents.store') }}">
    @csrf

    <div class="row">

      {{-- FIRST NAME --}}
      <div class="col-md-6 mb-3">
        <label>First name</label>
        <input name="first_name" class="form-control" value="{{ old('first_name') }}">
        @error('first_name') <div class='text-danger'>{{ $message }}</div> @enderror
      </div>

      {{-- LAST NAME --}}
      <div class="col-md-6 mb-3">
        <label>Last name</label>
        <input name="last_name" class="form-control" value="{{ old('last_name') }}">
        @error('last_name') <div class='text-danger'>{{ $message }}</div> @enderror
      </div>

      {{-- EMAIL --}}
      <div class="col-md-6 mb-3">
        <label>Email</label>
        <input name="email" class="form-control" value="{{ old('email') }}">
        @error('email') <div class='text-danger'>{{ $message }}</div> @enderror
      </div>

      {{-- COUNTRY --}}
      <div class="col-md-6 mb-3">
        <label>Country</label>
        <select name="country_id" id="country" class="form-control">
          <option value="">Select</option>
          @foreach($countries as $c)
            <option value="{{ $c->id }}" {{ old('country_id') == $c->id ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
        @error('country_id') <div class='text-danger'>{{ $message }}</div> @enderror
      </div>

      {{-- BIRTH DATE --}}
      <div class="col-md-6 mb-3">
        <label>Birth date</label>
        <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}">
        @error('birth_date') <div class='text-danger'>{{ $message }}</div> @enderror
      </div>

      {{-- AGE --}}
      <div class="col-md-6 mb-3">
        <label>Age</label>
        <input name="age" id="age" class="form-control" value="{{ old('age') }}" readonly>
      </div>

      {{-- STATE --}}
      <div class="col-md-6 mb-3">
        <label>State</label>
        <select name="state_id" id="state" class="form-control">
          <option value="">Select</option>
        </select>
        @error('state_id') <div class='text-danger'>{{ $message }}</div> @enderror
      </div>

      {{-- CITY --}}
      <div class="col-md-6 mb-3">
        <label>City</label>
        <select name="city_id" id="city" class="form-control">
          <option value="">Select</option>
        </select>
        @error('city_id') <div class='text-danger'>{{ $message }}</div> @enderror
      </div>

      {{-- Residential Proof --}}
      <div class="col-md-6 mb-3">
        <label>Residential proof</label>
        <input type="file" name="residential_proofs[]" class="form-control" multiple>
      </div>

      {{-- Profile Image --}}
      <div class="col-md-6 mb-3">
        <label>Profile image</label>
        <input type="file" name="profile_image" class="form-control">
      </div>

      {{-- EDUCATION --}}
      <div class="col-md-6 mb-3">
        <label>Education</label>
        <input name="education" class="form-control" value="{{ old('education') }}">
      </div>

      {{-- OCCUPATION --}}
      <div class="col-md-6 mb-3">
        <label>Occupation</label>
        <input name="occupation" class="form-control" value="{{ old('occupation') }}">
      </div>

      {{-- CHILDREN MULTI SELECT --}}
      <div class="col-md-12 mb-3">
        <label>Children (select multiple)</label>
        <select name="children[]" multiple class="form-control">
          @foreach($children as $c)
            <option value="{{ $c->id }}"
              {{ collect(old('children'))->contains($c->id) ? 'selected' : '' }}>
              {{ $c->first_name }} {{ $c->last_name }}
            </option>
          @endforeach
        </select>
      </div>

    </div>

    <button class="btn btn-primary">Save</button>
  </form>
</div>

<script>
// AUTO AGE
document.querySelector('#birth_date').addEventListener('change', function(){
  let b = new Date(this.value);
  if(!isNaN(b)){
    let diff = Date.now() - b.getTime();
    let age = new Date(diff).getUTCFullYear() - 1970;
    document.querySelector('#age').value = age;
  }
});

// COUNTRY → STATE
document.querySelector('#country').addEventListener('change', function(){
  let id = this.value;

  fetch('/get-states/' + id)
    .then(res => res.text())
    .then(data => {
      document.querySelector('#state').innerHTML = data;
      document.querySelector('#city').innerHTML = '<option value="">Select</option>';
    });
});

// STATE → CITY
document.querySelector('#state').addEventListener('change', function(){
  let id = this.value;
  
  fetch('/get-cities/' + id)
    .then(res => res.text())
    .then(data => {
      document.querySelector('#city').innerHTML = data;
    });
});
</script>
@endsection
