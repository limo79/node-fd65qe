<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\course_exam;
use App\Models\course_exam_student;
use App\Models\course_student;
use App\Models\group;
use App\Models\student;
use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class course_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=course::orderBy('start_date','desc')->paginate(10);
                return  view('admin.courses',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                $account=\session()->get('id');
                $data=course::where('teacher_id',$account)->orderBy('start_date','desc')->paginate(10);

                return  view('teacher.courses',compact('data'));
            }
            elseif(\session()->get('level')==2)
            {
                $account=\session()->get('id');
                $data=course::join('course_students','course_students.course_id','courses.id')->where('course_students.student_id',$account)->orderBy('start_date','desc')->paginate(10);

                return  view('student.courses',compact('data'));
            }
        }
        else
        {
            return  redirect('/');
        }


    }

    public function status($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                \session()->put('course_id',$id);
                return view('admin.courses_status');
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }
    }

    public function status_save($id,Request $request)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $form=$request->all();

                $tbl=course::find($id);
                $tbl->status=$form['status'];
                $tbl->save();

                return redirect('/admin/courses/'.$id.'/show');
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $groups=group::where('active',1)->get();
                $teachers=teacher::all();

                return view('admin.courses_add',compact('groups','teachers'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'name'=>'required',
                    'groups'=>'required |Numeric',
                    'teachers'=>'required',
                    'price'=>'required | Numeric',
                    'capacity'=>'required|Numeric',
                    'start_date'=>'required',
                    'end_date'=>'required',
                ]);
                $form=$request->all();

                 $filename='noPic.jpg';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }



                $id=rand(11111111,99999999);
                $tbl=new course();
                $tbl->id=$id;
                $tbl->title=$form['name'];
                $tbl->type=$form['type'];
                $tbl->text=$form['text'];
                $tbl->start_date=$form['start_date'];
                $tbl->end_date=$form['end_date'];
                $tbl->group_id=$form['groups'];
                $tbl->teacher_id=$form['teachers'];
                $tbl->from_time=$form['from_time'];
                $tbl->to_time=$form['to_time'];
                $tbl->days=$form['days'];
                $tbl->price=$form['price'];
                $tbl->capacity=$form['capacity'];
                $tbl->pic=$filename;
                $tbl->status=0;
                $tbl->save();

                Session::flash('message', 'اطلاعات با موفقیت ثبت گردید');
                Session::flash('alert-class', 'alert-success');

                return back();
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }


    }

    public function filter(Request  $request)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $form= $request->all();
                $drop=$form['dropFilter'];
                $search=$form['txtSearch'];

                if($drop==-1)
                {
                    if($search=="")
                    {
                        $data=course::orderByRaw('start_date desc')->paginate(5);
                    }
                    else
                    {

                        $data=course::join('teachers','teacher_id','teachers.id')->join('groups','group_id','groups.id')->where('title','LIKE','%'.$search.'%')->orWhere('groups.name','LIKE','%'.$search.'%')->orWhere('teachers.name','LIKE','%'.$search.'%')->orwhere('start_date','LIKE','%'.$search.'%')->orwhere('text','LIKE','%'.$search.'%')->orderByRaw('start_date desc')->paginate(10);
                    }
                }
                else
                {

                    $data=course::where('status',$drop)->orderByRaw('start_date desc')->paginate(5);
                }

                return view('admin.courses',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }


    }

    public function course_students($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                \session()->put('course_id',$id);
                $data=student::orderby('name','asc')->get();
                $students=course_student::where('course_id',$id)->get();
                return  view('admin.courses_students',compact('data','students'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }
    }

    public function student_add($id,Request $request)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'selected'=>'required',
                ]);

                $form=$request->all();

                foreach($form['selected'] as $f)
                {
                    $student_id= $f;
                    $data=course_student::where('course_id',$id)->where('student_id',$student_id)->get();
                    if(count($data)==0)
                    {
                        $tbl=new course_student();
                        $tbl->course_id=$id;
                        $tbl->student_id=$student_id;
                        $tbl->save();
                    }
                }

                return redirect('/admin/courses/'.$id.'/students');
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }
    }

    public function student_del($id,$student)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                course_student::where('course_id',$id)->where('id',$student)->delete();
                return redirect('/admin/courses/'.$id.'/students');
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(session()->has('id'))
        {
            \session()->put('course_id',$id);
            if(\session()->get('level')==0)
            {
                $data=course::find($id);
                return view('admin.courses_show',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                $data=course::find($id);
                return view('teacher.courses_show',compact('data'));
            }
            elseif(\session()->get('level')==2)
            {
                $account=\session()->get('id');
                $data=course_student::where('course_id',$id)->where('student_id',$account)->get();
                if(count($data)==0)
                {
                    \session()->put('allow',0);
                    return  redirect('/student');
                }
                else
                {
                    \session()->put('allow',1);
                    $data=course::find($id);
                    $exams=course_exam::where('course_id',$id)->get();

                    return view('student.courses_show',compact('data','exams'));
                }


            }
        }
        else
        {
            return  redirect('/');
        }


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $groups=group::where('active',1)->get();
                $data=course::find($id);
                $teachers=teacher::all();

                return view('admin.courses_edit',compact('data','groups','teachers'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'name'=>'required',
                    'groups'=>'required |Numeric',
                    'teachers'=>'required',
                    'price'=>'required | Numeric',
                    'capacity'=>'required|Numeric',
                    'start_date'=>'required',
                    'end_date'=>'required',
                ]);
                $form=$request->all();

                   $filename='';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }




                $tbl=course::find($id);
                $tbl->title=$form['name'];
                $tbl->type=$form['type'];
                $tbl->text=$form['text'];
                $tbl->start_date=$form['start_date'];
                $tbl->end_date=$form['end_date'];
                $tbl->group_id=$form['groups'];
                $tbl->teacher_id=$form['teachers'];
                $tbl->from_time=$form['from_time'];
                $tbl->to_time=$form['to_time'];
                $tbl->days=$form['days'];
                $tbl->price=$form['price'];
                $tbl->capacity=$form['capacity'];
                $tbl->pic=$filename;
                $tbl->save();

                return redirect('/admin/courses');
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=course::find($id);
                if(count($data->students)==0)
                {
                    $data->delete();
                    return redirect('/admin/courses');
                }
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return  redirect('/student');
            }
        }
        else
        {
            return  redirect('/');
        }
    }
}
