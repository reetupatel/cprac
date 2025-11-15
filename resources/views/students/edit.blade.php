@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
<div class="container my-4">
  <h3 class="mb-4">✏️ Edit Student</h3>

  <form id="studentForm" action="{{ route('students.update', base64_encode($student->id)) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}">
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}">
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Phone <span class="text-danger">*</span></label>
        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone) }}" placeholder="1234567890">
        @error('phone')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Age <span class="text-danger">*</span></label>
        <input type="number" name="age" class="form-control @error('age') is-invalid @enderror" value="{{ old('age', $student->age) }}">
        @error('age')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
        <input type="text" name="dob" class="form-control datepicker @error('dob') is-invalid @enderror" value="{{ old('dob', $student->dob) }}">
        @error('dob')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Preferred Time <span class="text-danger">*</span></label>
        <input type="time" name="preferred_time" class="form-control @error('preferred_time') is-invalid @enderror" value="{{ old('preferred_time', $student->preferred_time) }}">
        @error('preferred_time')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label d-block">Gender <span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
          <input type="radio" name="gender" value="male" class="form-check-input @error('gender') is-invalid @enderror" {{ old('gender', $student->gender)=='male' ? 'checked' : '' }}>
          <label class="form-check-label">Male</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="radio" name="gender" value="female" class="form-check-input @error('gender') is-invalid @enderror" {{ old('gender', $student->gender)=='female' ? 'checked' : '' }}>
          <label class="form-check-label">Female <span class="text-danger">*</span></label>
        </div>
        @error('gender')
          <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Skills (choose at least 1) <span class="text-danger">*</span></label>
        @php
            $oldSkills = old('skills', is_string($student->skills) ? json_decode($student->skills, true) : ($student->skills ?? []));
        @endphp
        <div class="form-check form-check-inline">
          <input type="checkbox" name="skills[]" value="PHP" class="form-check-input" {{ in_array('PHP', $oldSkills) ? 'checked' : '' }}>
          <label class="form-check-label">PHP</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" name="skills[]" value="JavaScript" class="form-check-input" {{ in_array('JavaScript', $oldSkills) ? 'checked' : '' }}>
          <label class="form-check-label">JavaScript</label>
        </div>
        <div class="form-check form-check-inline">
          <input type="checkbox" name="skills[]" value="Laravel" class="form-check-input" {{ in_array('Laravel', $oldSkills) ? 'checked' : '' }}>
          <label class="form-check-label">Laravel</label>
        </div>
        @error('skills')
          <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Country <span class="text-danger">*</span></label>
        <select name="country" class="form-select select2 @error('country') is-invalid @enderror">
          <option value="">Select Country</option>
          @foreach(['India','USA','UK'] as $country)
            <option value="{{ $country }}" {{ old('country', $student->country)==$country ? 'selected' : '' }}>{{ $country }}</option>
          @endforeach
        </select>
        @error('country')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Languages Known <span class="text-danger">*</span></label>
        @php
            $oldLanguages = old('languages', is_string($student->languages) ? json_decode($student->languages, true) : ($student->languages ?? []));
        @endphp
        <select name="languages[]" class="form-select select2 @error('languages') is-invalid @enderror" multiple>
          @foreach(['English','Hindi','Gujarati'] as $lang)
            <option value="{{ $lang }}" {{ in_array($lang, $oldLanguages) ? 'selected' : '' }}>{{ $lang }}</option>
          @endforeach
        </select>
        @error('languages')
          <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Profile Photo <span class="text-danger">*</span></label>
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" id="photo">
        @if($student->photo)
          <img id="photoPreview" src="{{ asset('storage/'.$student->photo) }}" alt="Current Photo" style="margin-top:10px; max-width:100px;">
        @else
          <img id="photoPreview" src="" alt="Preview" style="display:none; margin-top:10px; max-width:100px;">
        @endif
        @error('photo')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Favorite Color <span class="text-danger">*</span></label>
        <input type="color" name="fav_color" class="form-control form-control-color @error('fav_color') is-invalid @enderror" value="{{ old('fav_color', $student->fav_color) }}">
        @error('fav_color')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-12">
        <label class="form-label">About Student <span class="text-danger">*</span></label>
        <textarea name="about" class="form-control @error('about') is-invalid @enderror" rows="4">{{ old('about', $student->about) }}</textarea>
        @error('about')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
      </div>

    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){

  $('.select2').select2({ width: '100%' });

  $(".datepicker").datepicker({
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      changeYear: true,
      yearRange: "-100:+0"
  });

  $("#togglePassword").click(function(){
      let input = $("#password");
      let icon = $(this).find('i');
      if(input.attr("type") === "password"){
          input.attr("type", "text");
          icon.removeClass('bi-eye').addClass('bi-eye-slash');
      } else {
          input.attr("type", "password");
          icon.removeClass('bi-eye-slash').addClass('bi-eye');
      }
  });

  $('#photo').on('change', function() {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function(e) {
          $('#photoPreview').attr('src', e.target.result).show();
      };
      reader.readAsDataURL(file);
  });

  $("#studentForm").validate({
    errorClass: 'is-invalid',
    validClass: 'is-valid',
    errorElement: 'div',
    errorPlacement: function(error, element){
        error.addClass('invalid-feedback');
        if(element.prop('type') === 'checkbox' || element.prop('type') === 'radio'){
            element.closest('.col-md-6').append(error);
        } else if(element.attr('type') === 'password'){
            element.closest('.input-group').after(error);
        } else {
            error.insertAfter(element);
        }
    },
    rules: {
      name: { required: true, minlength: 3 },
      email: { required: true, email: true },
      password: {
          minlength: 8,
          pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
      },
      phone: { required: true, digits: true, minlength: 10, maxlength: 10 },
      age: { required: true, number: true, min: 1, max: 100 },
      dob: { required: true, date: true },
      preferred_time: { required: true },
      gender: { required: true },
      'skills[]': { required: true },
      country: { required: true },
      'languages[]': { required: true },
      photo: { extension: "jpg|jpeg|png" },
      about: { required: true, minlength: 10 },
      fav_color: { required: true }
    }
  });

});
</script>
@endsection