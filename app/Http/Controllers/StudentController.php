<?php

namespace App\Http\Controllers;

use App\Models\Student;
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

            Student::create($data);

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