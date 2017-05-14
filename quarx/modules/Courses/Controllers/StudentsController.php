<?php

namespace Quarx\Modules\Courses\Controllers;

use Quarx;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Quarx\Modules\Courses\Services\StudentService;
use Quarx\Modules\Courses\Requests\StudentCreateRequest;
use Quarx\Modules\Courses\Requests\StudentUpdateRequest;
use Quarx\Modules\Courses\Models\Student;

class StudentsController extends Controller
{
   private $inputs = null;
   
    public function __construct(StudentService $studentService, Request $request)
    {
        $this->service = $studentService;
        $this->inputs = $request->all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $students = $this->service->paginated();
        
        return view('courses::students.index')
            ->with('pagination', $students->render())
            ->with('students', $students);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $students = $this->service->search($request->search);
        return view('courses::students.index')
            ->with('term', $request->search)
            ->with('pagination', $courses->render())
            ->with('students', $students);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       $input = $request->all();
       
       $course = \DB::table('courses')
           ->where('id', '=', $input['course'])
           ->first();
           
       $sections = \DB::table('course_sections')
           ->where('course_id', '=', $input['course'])
           ->orderBy('courseorder')
           ->get();
       
        return view('courses::students.create')
            ->with('sections', $sections)
            ->with('course', $course);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StudentCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Quarx::notification('Successfully created', 'success');
            return redirect('quarx/students/'.$result->id.'/edit');
        }

        Quarx::notification('Failed to create', 'warning');
        return redirect('quarx/students');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $course = \DB::table('courses')
       ->where('id', '=', $this->inputs['course'])
       ->first();
       
        $student = $this->service->find($id);
        return view('courses::students.show')
            ->with('student', $student)
            ->with('course', $course);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = $this->service->find($id);
        
        $course = \DB::table('courses')
        ->where('id', '=', $student->course_id)
        ->first();       
        
        return view('courses::students.edit')
            ->with('student', $student)
            ->with('course', $course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\StudentUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentUpdateRequest $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            Quarx::notification('Successfully updated', 'success');
            return back();
        }

        Quarx::notification('Failed to update', 'warning');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            Quarx::notification('Successfully deleted', 'success');
            return redirect('quarx/students');
        }

        Quarx::notification('Failed to delete', 'warning');
        return redirect('quarx/students');
    }
}
