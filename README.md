# 📌 **REETU PRACTICAL – Laravel 10 + Bootstrap Auth + Datatables**

This project is a Laravel 10 application using **Laravel/UI Bootstrap Auth**,
**jQuery**, **Select2**, **jQuery Validation**, **Datatables**, and **Toast Messages**.

---

## 🚀 **Installation Steps**

### **1. Create Laravel Project**

```bash
composer create-project laravel/laravel="10.0.*" reetu-practical
```

---

## 🔧 **2. Install Laravel UI**

```bash
composer require laravel/ui
php artisan ui bootstrap --auth
```

This will generate:

* Login & Register pages
* Bootstrap UI scaffolding
* Auth routes & controllers

---

## 📁 **3. Storage Link**

Make storage files publicly accessible:

```bash
php artisan storage:link
```

---

## 📊 **4. Install Datatables**

```bash
composer require yajra/laravel-datatables-oracle
```

Optional (publish config):

```bash
php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider"
```

---

## 🎨 **5. Add CSS in `layouts/app.blade.php`**

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

@yield('styles')
```

---

## 🧩 **6. Add JS in `app.blade.php`**

```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

@yield('scripts')
```

---

## 🔔 **7. Toast Notifications**

Auto dismiss toast messages for sessions:

```html
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <div class="toast-container position-fixed top-0 end-0 p-3">
        @foreach (['success', 'error', 'info', 'warning'] as $msg)
        @if(session($msg))
            <div class="toast align-items-center 
                @if($msg=='success') text-bg-success 
                @elseif($msg=='error') text-bg-danger
                @elseif($msg=='info') text-bg-info
                @elseif($msg=='warning') text-bg-warning 
                @endif
                border-0 show">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session($msg) }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        @endif
        @endforeach
    </div>
</div>
```

JS:

```html
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toast').forEach(function (toastEl) {
        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    });
});
</script>
```

---

## 📌 **8. Fixed Bottom Footer**

```html
<footer class="bg-light text-center py-3 border-top fixed-bottom">
    <div class="container">
        © {{ date('Y') }} {{ config('app.name', 'Laravel') }} — All Rights Reserved.
    </div>
</footer>
```

---

## 📸 Image Upload Helper (Trait)

Store inside `app/Traits/ImageHelperTrait.php`:

```php
<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageHelperTrait
{
    public function uploadImage($file, $folder = 'uploads')
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs($folder, $filename, 'public');

        return $folder . '/' . $filename;
    }

    public function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function getImage($path, $default = 'images/default.png')
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset($default);
    }
}
```

---

# ✅ **1. Create Student Model, Migration, Controller, Resource**

Run:

```bash
php artisan make:model Student -mcr
```

This creates:

* `app/Models/Student.php`
* `database/migrations/xxxxxx_create_students_table.php`
* `app/Http/Controllers/StudentController.php`
* Resource routes (add manually)

---

# ✅ **2. Migration (database/migrations/..students_table.php)**

```php
public function up(): void
{
    Schema::create('students', function (Blueprint $t) {
        $t->id();
        $t->string('name');
        $t->string('email')->nullable();
        $t->string('phone')->nullable();
        $t->integer('age')->nullable();
        $t->string('gender')->nullable();
        $t->json('skills')->nullable();
        $t->string('country')->nullable();
        $t->json('languages')->nullable();
        $t->date('dob')->nullable();
        $t->time('preferred_time')->nullable();
        $t->integer('hours')->nullable();
        $t->string('fav_color')->nullable();
        $t->string('photo')->nullable();
        $t->text('about')->nullable();
        $t->timestamps();
    });
}
```

Run migration:

```bash
php artisan migrate
```

---

# ✅ **3. Model (app/Models/Student.php)**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','phone','age','gender','skills','country','languages',
        'dob','preferred_time','hours','fav_color','photo','about'
    ];

    protected $casts = [
        'skills' => 'array',
        'languages' => 'array',
        'dob' => 'date'
    ];

    public function qualifications()
    {
        return $this->hasMany(StudentQualification::class)->orderBy('id', 'asc');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'course', 'passing_year', 'percentage'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
```

---

# 🔥 **4. Controller (FULL WORKING CRUD + Image Upload)**

`app/Http/Controllers/StudentController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentQualification;
use Illuminate\Http\Request;
use App\Traits\ImageHelperTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Student\StoreRequest;
use App\Http\Requests\Student\UpdateRequest;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    use ImageHelperTrait;

    private $studentModelName = 'Student';

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $students = Student::select(['id', 'name', 'email', 'created_at']);
                return DataTables::of($students)
                    ->addIndexColumn()
                    ->addColumn('action', function($student){
                        $editUrl = route('students.edit', base64_encode($student->id));
                        $deleteUrl = route('students.destroy', base64_encode($student->id));

                        $csrf = csrf_field();
                        $method = method_field('DELETE');

                        $buttons = <<<HTML
                        <a href="{$editUrl}" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{$deleteUrl}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this student?');">
                            {$csrf}
                            {$method}
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        HTML;

                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            
            return view('students.index');
        }catch (\Exception $e) {
            Log::error('Failed to load students list page: '.$e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the students list. Please try again.');
        }
        
    }

    public function create()
    {
        try {
            return view('students.create');
        } catch (\Exception $e) {
            Log::error('Failed to load create student page: '.$e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the student registration form. Please try again.');
        }
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $data['photo'] = $this->uploadImage($request->file('photo'), 'students');
            }

            $data['subjects'] = json_encode($data['subjects'] ?? []);
            $data['hobbies'] = json_encode($data['hobbies'] ?? []);

            $student = Student::create($data);

            foreach ($request->qualification as $q) {
                StudentQualification::create([
                    'student_id'    => $student->id,
                    'course'        => $q['course'],
                    'passing_year'  => $q['year'],
                    'percentage'    => $q['percentage'],
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', $this->studentModelName . ' created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student creation failed: '.$e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            $student = Student::findOrFail(base64_decode($id));

            if (!$student) {
                return redirect()->back()->with('error', $this->studentModelName . ' not found.');
            }

            return view('students.edit', compact('student'));
        } catch (\Exception $e) {
            Log::error('Failed to load edit student page: '.$e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the student edit form. Please try again.');
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        $decodedId = base64_decode($id);

        $student = Student::find($decodedId);

        if (!$student) {
            return redirect()->back()->with('error', $this->studentModelName . ' not found.');
        }

        DB::beginTransaction();

        try {
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            if ($request->hasFile('photo')) {
                if (isset($student->photo)) {
                    $this->deleteImage($student->photo);
                }

                $data['photo'] = $this->uploadImage($request->file('photo'), 'students');
            }

            $data['skills'] = json_encode($data['skills'] ?? []);
            $data['languages'] = json_encode($data['languages'] ?? []);

            $student->update($data);


            $existingIds = $student->qualifications->pluck('id')->toArray();
            $submittedIds = [];

            foreach ($request->qualification as $row) {

                if (!empty($row['id'])) {
                    // Update existing row
                    $submittedIds[] = $row['id'];

                    StudentQualification::where('id', $row['id'])->update([
                        'course' => $row['course'],
                        'passing_year' => $row['year'],
                        'percentage' => $row['percentage']
                    ]);
                } 
                else {
                    // Create new row
                    StudentQualification::create([
                        'student_id' => $student->id,
                        'course' => $row['course'],
                        'passing_year' => $row['year'],
                        'percentage' => $row['percentage']
                    ]);
                }
            }

            // Delete removed qualifications
            $deleteIds = array_diff($existingIds, $submittedIds);
            if ($deleteIds) {
                StudentQualification::whereIn('id', $deleteIds)->delete();
            }

            DB::commit();

            return redirect()->route('students.index')->with('success', $this->studentModelName . ' updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student update failed: '.$e->getMessage());

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function destroy($id)
    {
        try{
            $decodedId = base64_decode($id);

            $student = Student::find($decodedId);

            if (!$student) {
                return redirect()->back()->with('error', $this->studentModelName . ' not found.');
            }

            if (isset($student->photo)) {
                $this->deleteImage($student->photo);
            }

            $student->delete();
            return redirect()->back()->with('success', $this->studentModelName . ' deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Student delete failed: '.$e->getMessage());

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Something went wrong! Please try again.');
        }
    }
}
```

---

# 📌 **5. Routes**

Add in `routes/web.php`:

```php
Route::middleware(['auth'])->group(function () {
    Route::resource('students', StudentController::class);
});
```

---

# 🎨 **6. View Files**

Create folder:

```
resources/views/students/
```

---

## **students/index.blade.php**

```html
@extends('layouts.app')

@section('title', 'Students List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Students List</h4>
    <a href="{{ route('students.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Add Student
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table id="students-table" class="table table-striped table-bordered align-middle w-100">
            <thead class="table-light">
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th width="15%">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('#students-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('students.index') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
    });

    // Confirm before delete
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this student?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endsection
```

---

## **students/create.blade.php**

```html
  @extends('layouts.app')

  @section('title', 'Student Registration')

  @section('styles')

  @endsection

  @section('content')
<div class="container my-4">
  <h3 class="mb-4">🎓 Student Registration</h3>

  <form id="studentForm" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="row g-3">

      <div class="col-md-6">
        <label class="form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" placeholder="Enter full name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" placeholder="example@gmail.com" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6 position-relative">
        <label class="form-label">Password <span class="text-danger">*</span></label>
        <div class="input-group">
          <input type="password" name="password" placeholder="Enter password" class="form-control @error('password') is-invalid @enderror" id="password">
          <span class="input-group-text" id="togglePassword" style="cursor:pointer">
            <i class="bi bi-eye"></i>
          </span>
        </div>
        <small class="form-text text-muted">
          Password must be 8+ characters, uppercase, number & special character.
        </small>
        @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Phone <span class="text-danger">*</span></label>
        <input type="tel" name="phone" placeholder="1234567890" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Age <span class="text-danger">*</span></label>
        <input type="number" name="age" placeholder="20" class="form-control @error('age') is-invalid @enderror" value="{{ old('age') }}">
        @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
        <input type="text" name="dob" placeholder="YYYY-MM-DD" class="form-control datepicker @error('dob') is-invalid @enderror" value="{{ old('dob') }}">
        @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-4">
        <label class="form-label">Preferred Time <span class="text-danger">*</span></label>
        <input type="time" name="preferred_time" placeholder="Select time" class="form-control @error('preferred_time') is-invalid @enderror" value="{{ old('preferred_time') }}">
        @error('preferred_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label d-block">Gender <span class="text-danger">*</span></label>
        <div class="form-check form-check-inline">
          <input type="radio" name="gender" value="male" class="form-check-input @error('gender') is-invalid @enderror" {{ old('gender')=='male' ? 'checked' : '' }}>
          <label class="form-check-label">Male</label>
        </div>

        <div class="form-check form-check-inline">
          <input type="radio" name="gender" value="female" class="form-check-input @error('gender') is-invalid @enderror" {{ old('gender')=='female' ? 'checked' : '' }}>
          <label class="form-check-label">Female</label>
        </div>
        @error('gender') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Skills (choose at least 1) <span class="text-danger">*</span></label>
        @php $oldSkills = old('skills', []); @endphp
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

        @error('skills') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Country <span class="text-danger">*</span></label>
        <select name="country" class="form-select select2 @error('country') is-invalid @enderror">
          <option value="">Select Country</option>
          <option value="India" {{ old('country')=='India' ? 'selected' : '' }}>India</option>
          <option value="USA" {{ old('country')=='USA' ? 'selected' : '' }}>USA</option>
          <option value="UK" {{ old('country')=='UK' ? 'selected' : '' }}>UK</option>
        </select>
        @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Languages Known <span class="text-danger">*</span></label>
        @php $oldLanguages = old('languages', []); @endphp
        <select name="languages[]" class="form-select select2 @error('languages') is-invalid @enderror" multiple>
          <option value="English" {{ in_array('English', $oldLanguages) ? 'selected' : '' }}>English</option>
          <option value="Hindi" {{ in_array('Hindi', $oldLanguages) ? 'selected' : '' }}>Hindi</option>
          <option value="Gujarati" {{ in_array('Gujarati', $oldLanguages) ? 'selected' : '' }}>Gujarati</option>
        </select>
        @error('languages') <div class="text-danger mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Profile Photo (jpg/png) <span class="text-danger">*</span></label>
        <input type="file" name="photo" placeholder="Upload image" class="form-control @error('photo') is-invalid @enderror" id="photo">
        <img id="photoPreview" src="" alt="Preview" style="display:none; margin-top:10px; max-width:100px;">
        @error('photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-6">
        <label class="form-label">Favorite Color <span class="text-danger">*</span></label>
        <input type="color" name="fav_color" placeholder="Choose color" class="form-control form-control-color @error('fav_color') is-invalid @enderror" value="{{ old('fav_color') }}">
        @error('fav_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>

      <div class="col-md-12">
        <label class="form-label">About Student <span class="text-danger">*</span></label>
        <textarea name="about" placeholder="Write about student..." class="form-control @error('about') is-invalid @enderror" rows="4">{{ old('about') }}</textarea>
        @error('about') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <!-- QUALIFICATIONS -->
      <div class="col-12 mt-4">
        <h5 class="mb-3">Qualifications</h5>

        <table class="table table-bordered" id="qualificationTable">
          <thead class="table-light">
            <tr>
              <th>Course Name</th>
              <th>Passing Year</th>
              <th>Percentage</th>
              <th width="50">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>
                <input type="text" name="qualification[0][course]" placeholder="Enter cource name" class="form-control course-input" required>
              </td>
              <td>
                <input type="number" name="qualification[0][year]" placeholder="Enter year" class="form-control" required min="1900" max="2099">
              </td>
              <td>
                <input type="number" name="qualification[0][percentage]" placeholder="Enter percentage" class="form-control" required min="1" max="100">
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-success btn-sm addRow">+</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
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

        console.log({ name: file.name, size: file.size, type: file.type });

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
            required: true,
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
        photo: { required: true, extension: "jpg|jpeg|png" },
        about: { required: true, minlength: 10 },
        fav_color: { required: true }
      },
      messages: {
          name: {
              required: "Full name is required.",
              minlength: "Full name must be at least 3 characters."
          },
          email: {
              required: "Email is required.",
              email: "Please enter a valid email address."
          },
          password: {
              required: "Password is required.",
              minlength: "Password must be at least 8 characters.",
              pattern: "Password must include uppercase, lowercase, number & special character."
          },
          phone: {
              required: "Phone number is required.",
              digits: "Only digits are allowed.",
              minlength: "Phone number must be 10 digits.",
              maxlength: "Phone number must be 10 digits."
          },
          age: {
              required: "Age is required.",
              number: "Age must be a number.",
              min: "Age cannot be less than 1.",
              max: "Age cannot be more than 100."
          },
          dob: {
              required: "Date of Birth is required.",
              date: "Please enter a valid date."
          },
          preferred_time: {
              required: "Preferred time is required."
          },
          gender: {
              required: "Please select your gender."
          },
          'skills[]': {
              required: "Please select at least one skill."
          },
          country: {
              required: "Country is required."
          },
          'languages[]': {
              required: "Please select at least one language."
          },
          photo: {
              required: "Profile photo is required.",
              extension: "Only jpg, jpeg, or png files are allowed."
          },
          about: {
              required: "Please write something about the student.",
              minlength: "About section must be at least 10 characters."
          },
          fav_color: {
              required: "Please select your favorite color."
          }
      }
    });

  let rowIndex = 1;

  // Unique Course Rule
  $.validator.addMethod("uniqueCourse", function(value, element){
      let courses = [];

      $(".course-input").each(function(){
          let val = $(this).val().trim().toLowerCase();
          if(val) courses.push(val);
      });

      let count = courses.filter(v => v === value.trim().toLowerCase()).length;
      return count <= 1;
  }, "Course name already added");

    // Function to add validation rules to row inputs
    function bindQualificationValidation() {
      $(".course-input").each(function(){
          $(this).rules("add", {
              required: true,
              minlength: 2,
              uniqueCourse: true,
              messages: {
                  required: "Course is required",
                  minlength: "Min 2 letters",
                  uniqueCourse: "Course name already added"
              }
          });
      });

      $("input[name*='[year]']").each(function(){
          $(this).rules("add", {
              required: true,
              number: true,
              min: 1900,
              max: 2099,
              messages: {
                required: "Year required",
                number: "Only number",
                min: "Invalid year",
                max: "Invalid year"
              }
          });
      });

      $("input[name*='[percentage]']").each(function(){
          $(this).rules("add", {
              required: true,
              number: true,
              min: 1,
              max: 100,
              messages: {
                required: "Percentage required",
                min: "Min 1",
                max: "Max 100"
              }
          });
      });
    }

// Bind validation for first row
bindQualificationValidation();

// Add row
$(document).on("click", ".addRow", function(){
    let newRow = `
    <tr>
        <td><input type="text" placeholder="Enter cource name" name="qualification[${rowIndex}][course]" class="form-control course-input"></td>
        <td><input type="number" placeholder="Enter year" name="qualification[${rowIndex}][year]" class="form-control"></td>
        <td><input type="number" placeholder="Enter percentage" name="qualification[${rowIndex}][percentage]" class="form-control"></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm removeRow">−</button>
        </td>
    </tr>`;

    $("#qualificationTable tbody").append(newRow);
    rowIndex++;

    bindQualificationValidation(); 
});

// Remove Row
$(document).on("click", ".removeRow", function(){
    $(this).closest("tr").remove();
    validator.form();
});

  });
  </script>
  @endsection
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

      <div class="col-12 mt-4">
        <h5>Qualifications</h5>

        <table class="table table-bordered" id="qualificationTable">
          <thead class="table-light">
            <tr>
              <th>Course</th>
              <th>Year</th>
              <th>Percentage</th>
              <th width="50">Action</th>
            </tr>
          </thead>
          <tbody>
            @php $i=0; @endphp

            @foreach($student->qualifications as $q)
            <tr>
              <td>
                <input type="hidden" name="qualification[{{ $i }}][id]" value="{{ $q->id }}">
                <input type="text" name="qualification[{{ $i }}][course]" value="{{ $q->course }}" class="form-control course-input">
              </td>
              <td><input type="number" name="qualification[{{ $i }}][year]" value="{{ $q->passing_year }}" class="form-control"></td>
              <td><input type="number" name="qualification[{{ $i }}][percentage]" value="{{ $q->percentage }}" class="form-control"></td>
              <td class="text-center">
                @if($i==0)
                  <button type="button" class="btn btn-success btn-sm addRow">+</button>
                @else
                  <button type="button" class="btn btn-danger btn-sm removeRow">−</button>
                @endif
              </td>
            </tr>
            @php $i++; @endphp
            @endforeach

          </tbody>
        </table>
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

  var rowIndex = $("#qualificationTable tbody tr").length;

// Unique Course Rule
$.validator.addMethod("uniqueCourse", function(value, element){
    let courses = [];

    $(".course-input").each(function(){
        let val = $(this).val().trim().toLowerCase();
        if(val) courses.push(val);
    });

    let count = courses.filter(v => v === value.trim().toLowerCase()).length;
    return count <= 1;
}, "Course name already added");

  // Function to add validation rules to row inputs
  function bindQualificationValidation() {
    $(".course-input").each(function(){
        $(this).rules("add", {
            required: true,
            minlength: 2,
            uniqueCourse: true,
            messages: {
                required: "Course is required",
                minlength: "Min 2 letters",
                uniqueCourse: "Course name already added"
            }
        });
    });

    $("input[name*='[year]']").each(function(){
        $(this).rules("add", {
            required: true,
            number: true,
            min: 1900,
            max: 2099,
            messages: {
              required: "Year required",
              number: "Only number",
              min: "Invalid year",
              max: "Invalid year"
            }
        });
    });

    $("input[name*='[percentage]']").each(function(){
        $(this).rules("add", {
            required: true,
            number: true,
            min: 1,
            max: 100,
            messages: {
              required: "Percentage required",
              min: "Min 1",
              max: "Max 100"
            }
        });
    });
  }

// Bind validation for first row
bindQualificationValidation();

// Add row
$(document).on("click", ".addRow", function(){
  let newRow = `
  <tr>
  <td>
          <input type="hidden" name="qualification[${rowIndex}][id]" value="">
          <input type="text" name="qualification[${rowIndex}][course]" class="form-control course-input" placeholder="Enter course">
        </td>
      <td><input type="number" placeholder="Enter year" name="qualification[${rowIndex}][year]" class="form-control"></td>
      <td><input type="number" placeholder="Enter percentage" name="qualification[${rowIndex}][percentage]" class="form-control"></td>
      <td class="text-center">
          <button type="button" class="btn btn-danger btn-sm removeRow">−</button>
      </td>
  </tr>`;

  $("#qualificationTable tbody").append(newRow);
  rowIndex++;

  bindQualificationValidation(); 
});

// Remove Row
$(document).on("click", ".removeRow", function(){
  $(this).closest("tr").remove();
  validator.form();
});

});
</script>
@endsection
```
---

## 🛠 **Generate Form Request Classes**

Run the following commands:

```bash
php artisan make:request Student/StoreRequest
php artisan make:request Student/UpdateRequest
```
---

# ✅ **StoreRequest.php**

```php
<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:students,email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'phone' => ['required', 'digits:10'],
            'age' => ['required', 'integer', 'min:1', 'max:100'],
            'dob' => ['required', 'date'],
            'preferred_time' => ['required'],
            'gender' => ['required', 'in:male,female'],
            'skills' => ['required', 'array', 'min:1'],
            'skills.*' => ['string'],
            'country' => ['required', 'string'],
            'languages' => ['required', 'array', 'min:1'],
            'languages.*' => ['string'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
            'about' => ['required', 'string', 'min:10'],
            'fav_color' => ['required', 'string'],
            'qualification'                   => 'required|array|min:1',
            'qualification.*.course'          => 'required|string|max:255',
            'qualification.*.year'            => 'required|integer|min:1900|max:2099',
            'qualification.*.percentage'      => 'required|integer|min:1|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Full name is required.',
            'name.min' => 'Full name must be at least 3 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, number & special character.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be 10 digits.',
            'age.required' => 'Age is required.',
            'age.integer' => 'Age must be a number.',
            'age.min' => 'Age cannot be less than 1.',
            'age.max' => 'Age cannot be more than 100.',
            'dob.required' => 'Date of Birth is required.',
            'dob.date' => 'Please enter a valid date.',
            'preferred_time.required' => 'Preferred time is required.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Gender must be male or female.',
            'skills.required' => 'Please select at least one skill.',
            'country.required' => 'Country is required.',
            'languages.required' => 'Please select at least one language.',
            'photo.required' => 'Profile photo is required.',
            'photo.mimes' => 'Only jpg, jpeg, or png files are allowed.',
            'about.required' => 'Please write something about the student.',
            'about.min' => 'About section must be at least 10 characters.',
            'fav_color.required' => 'Please select your favorite color.'
        ];
    }
}
```

---

# ✅ **UpdateRequest.php**

```php
<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $decodedId = base64_decode($this->route('student'));

        return [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => [
                'required',
                'email',
                Rule::unique('students', 'email')->ignore($decodedId, 'id')
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'phone' => ['required', 'digits:10'],
            'age' => ['required', 'integer', 'min:1', 'max:100'],
            'dob' => ['required', 'date'],
            'preferred_time' => ['required'],
            'gender' => ['required', 'in:male,female'],
            'skills' => ['required', 'array', 'min:1'],
            'skills.*' => ['string'],
            'country' => ['required', 'string'],
            'languages' => ['required', 'array', 'min:1'],
            'languages.*' => ['string'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
            'about' => ['required', 'string', 'min:10'],
            'fav_color' => ['required', 'string'],
            'qualification'                   => 'required|array|min:1',
            'qualification.*.course'          => 'required|string|max:255',
            'qualification.*.year'            => 'required|integer|min:1900|max:2099',
            'qualification.*.percentage'      => 'required|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.min' => 'Full name must be at least 3 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, number & special character.',
            'phone.required' => 'Phone number is required.',
            'phone.digits' => 'Phone number must be 10 digits.',
            'age.required' => 'Age is required.',
            'age.integer' => 'Age must be a number.',
            'age.min' => 'Age cannot be less than 1.',
            'age.max' => 'Age cannot be more than 100.',
            'dob.required' => 'Date of Birth is required.',
            'dob.date' => 'Please enter a valid date.',
            'preferred_time.required' => 'Preferred time is required.',
            'gender.required' => 'Please select your gender.',
            'gender.in' => 'Gender must be male or female.',
            'skills.required' => 'Please select at least one skill.',
            'country.required' => 'Country is required.',
            'languages.required' => 'Please select at least one language.',
            'photo.mimes' => 'Only jpg, jpeg, or png files are allowed.',
            'photo.max' => 'Maximum file size is 2MB.',
            'about.required' => 'Please write something about the student.',
            'about.min' => 'About section must be at least 10 characters.',
            'fav_color.required' => 'Please select your favorite color.',
        ];
    }
}
```

---

## app.blade.php

```bash
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    
    <!-- Datatable css -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                                    <i class="bi bi-people-fill"></i> Students
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="bg-light text-center py-3 border-top fixed-bottom">
            <div class="container">
                <span class="text-muted">
                    © {{ date('Y') }} {{ config('app.name', 'Laravel') }} — All Rights Reserved.
                </span>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Datable JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Toast Notifications -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed top-0 end-0 p-3">
            @foreach (['success', 'error', 'info', 'warning'] as $msg)
            @if(session($msg))
                <div class="toast align-items-center 
                    @if($msg=='success') text-bg-success 
                    @elseif($msg=='error') text-bg-danger
                    @elseif($msg=='info') text-bg-info
                    @elseif($msg=='warning') text-bg-warning 
                    @endif
                border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                    {{ session($msg) }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                </div>
            @endif
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 5000 }) // 5 seconds
            })
            toastList.forEach(toast => toast.show())
        });
        </script>


    @yield('scripts')
</body>
</html>

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('course');
            $table->integer('passing_year');
            $table->integer('percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_qualifications');
    }
};



---

## 🧪 Run the Project

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000/
```
