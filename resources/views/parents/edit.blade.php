@extends('layouts.app')

@section('content')
<div class="container">
  <h3>Edit Parent</h3>

  <form method="post" enctype="multipart/form-data" action="{{ route('parents.update', $parent->id) }}">
    @csrf
    @method('PUT')

    <div class="row">

      <div class="col-md-6 mb-3">
        <label>First name</label>
        <input name="first_name" class="form-control" value="{{ $parent->first_name }}">
      </div>

      <div class="col-md-6 mb-3">
        <label>Last name</label>
        <input name="last_name" class="form-control" value="{{ $parent->last_name }}">
      </div>

      <div class="col-md-6 mb-3">
        <label>Email</label>
        <input name="email" class="form-control" value="{{ $parent->email }}">
      </div>

      {{-- COUNTRY --}}
      <div class="col-md-6 mb-3">
        <label>Country</label>
        <select name="country_id" id="country" class="form-control">
          <option value="">Select</option>
          @foreach($countries as $c)
            <option value="{{ $c->id }}" {{ $parent->country_id == $c->id ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- STATE --}}
      <div class="col-md-6 mb-3">
        <label>State</label>
        <select name="state_id" id="state" class="form-control">
          @foreach($states as $s)
            <option value="{{ $s->id }}" {{ $parent->state_id == $s->id ? 'selected' : '' }}>
              {{ $s->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- CITY --}}
      <div class="col-md-6 mb-3">
        <label>City</label>
        <select name="city_id" id="city" class="form-control">
          @foreach($cities as $ci)
            <option value="{{ $ci->id }}" {{ $parent->city_id == $ci->id ? 'selected' : '' }}>
              {{ $ci->name }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- AGE --}}
      <div class="col-md-6 mb-3">
        <label>Birth date</label>
        <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ $parent->birth_date }}">
      </div>

      <div class="col-md-6 mb-3">
        <label>Age</label>
        <input name="age" id="age" class="form-control" value="{{ $parent->age }}" readonly>
      </div>

      {{-- Show uploaded files --}}
      <div class="col-md-12">
        <h5>Uploaded files</h5>
        <div class="row">

            @php 
                $files = DB::table('files')->where('parent_id', $parent->id)->get(); 
            @endphp

            @foreach($files as $f)
                <div class="col-md-3">
                    <div class="card mb-2 shadow-sm">
                        <div class="card-body text-center">

                            <p class="small mb-2">{{ basename($f->path) }}</p>

                            {{-- VIEW BUTTON --}}
                            <button 
                                type="button" 
                                class="btn btn-sm btn-primary view-file"
                                data-path="{{ asset('storage/'.$f->path) }}">
                                View
                            </button>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>


      {{-- CHILDREN CHECKBOXES --}}
      <div class="col-md-12 mt-3">
        <h5>Related children</h5>
        <table class="table">
          <thead><tr><th></th><th>Name</th><th>Email</th></tr></thead>
          <tbody>
            @foreach($children as $child)
              <tr>
                <td><input type="checkbox" name="children[]" value="{{ $child->id }}" 
                  {{ $parent->children->contains($child->id) ? 'checked' : '' }}></td>
                <td>{{ $child->first_name }} {{ $child->last_name }}</td>
                <td>{{ $child->email }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

    </div>

    <button class="btn btn-primary mt-3">Update</button>
  </form>
</div>

{{-- SIMPLE SCRIPTS --}}
<script>
  // AGE
  document.querySelector('#birth_date').addEventListener('change', function(){
    let b = new Date(this.value);
    if(!isNaN(b)){
      let diff = Date.now() - b.getTime();
      let age = new Date(diff).getUTCFullYear() - 1970;
      document.querySelector('#age').value = age;
    }
  });

  // Country → States
  document.querySelector('#country').addEventListener('change', function(){
    let id = this.value;

    fetch('/get-states/' + id)
      .then(res => res.text())
      .then(data => {
        document.querySelector('#state').innerHTML = data;
        document.querySelector('#city').innerHTML = '<option value="">Select</option>';
      });
  });

  // State → Cities
  document.querySelector('#state').addEventListener('change', function(){
    let id = this.value;

    fetch('/get-cities/' + id)
      .then(res => res.text())
      .then(data => {
        document.querySelector('#city').innerHTML = data;
      });
  });

  // View file in modal
  document.querySelectorAll('.view-file').forEach(btn => {
    btn.addEventListener('click', function(){
      document.querySelector('#filePreview').src = this.dataset.path;
      new bootstrap.Modal(document.getElementById('fileModal')).show();
    });
  });
</script>

{{-- File modal --}}
<div class="modal fade" id="fileModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
        <img id="filePreview" class="img-fluid">
      </div>
    </div>
  </div>
</div>
@endsection
