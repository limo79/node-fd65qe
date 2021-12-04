<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\course_exam;
use App\Models\course_exam_answer;
use App\Models\course_exam_question;
use App\Models\course_exam_student;
use App\Models\group;
use App\Models\question;
use App\Models\student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class exam_controller extends Controller
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
                $data=course_exam::orderByRaw('start_date desc,from_time desc')->paginate(10);
                return  view('admin.exams',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
               /* $data=course_exam::orderByRaw('start_date desc,from_time desc')->paginate(10);
                return  view('admin.exams',compact('data'));*/
            }
            elseif(\session()->get('level')==2)
            {

                $account=session()->get('id');
                $data=course_exam_student::where('student_id',$account)->paginate(10);

                return  view('student.exams',compact('data'));
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
                $data=course::all();
                return view('admin.exams_add',compact('data'));
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
                    'course'=>'required |Numeric',
                    'start_date'=>'required',
                    'from_time'=>'required',
                    'to_time'=>'required',
                ]);

                $form=$request->all();

                $tbl=new course_exam();
                $tbl->title=$form['name'];
                $tbl->course_id=$form['course'];
                $tbl->type=0;
                $tbl->text=$form['text'];
                $tbl->start_date=$form['start_date'];
                $tbl->from_time=$form['from_time'];
                $tbl->to_time=$form['to_time'];
                $tbl->total=0;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
                        $data=course_exam::orderByRaw('start_date desc')->paginate(5);
                    }
                    else
                    {

                        $data=course_exam::join('courses','course_id','courses.id')->where('course_exams.title','LIKE','%'.$search.'%')->orWhere('courses.title','LIKE','%'.$search.'%')->orwhere('course_exams.start_date','LIKE','%'.$search.'%')->orwhere('course_exams.text','LIKE','%'.$search.'%')->orderByRaw('course_exams.start_date desc')->paginate(10);
                    }
                }
                else
                {

                    $data=course_exam::where('status',$drop)->orderByRaw('start_date desc')->paginate(10);
                }

                return view('admin.exams',compact('data'));
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
    public function show($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=course_exam::find($id);
                return  view('admin.exams_show',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {

               $data=course_exam::find($id);
               $account=\session()->get('id');
               $exist=course_exam_student::where('course_exam_id',$id)->where('student_id',$account)->get();
               if(count($exist)==0)
               {
                   return  redirect('/student');
               }
               else
               {
                    return view('student.exams_show',compact('data'));
               }

            }
        }
        else
        {
            return  redirect('/');
        }
    }

    public function exam_students($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                \session()->put('exam_id',$id);
                $data=student::orderby('name','asc')->get();
                $students=course_exam_student::where('course_exam_id',$id)->get();

                return  view('admin.exams_students',compact('data','students'));
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
                    $data=course_exam_student::where('course_exam_id',$id)->where('student_id',$student_id)->get();
                    if(count($data)==0)
                    {
                        $tbl=new course_exam_student();
                        $tbl->course_exam_id=$id;
                        $tbl->student_id=$student_id;
                        $tbl->score=0;
                        $tbl->status=0;
                        $tbl->save();
                    }
                }

                return redirect('/admin/exams/'.$id.'/students');
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
                course_exam_student::where('course_exam_id',$id)->where('id',$student)->delete();
                return redirect('/admin/exams/'.$id.'/students');
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


    public function status($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                \session()->put('exam_id',$id);
                return view('admin.exams_status');
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

                $tbl=course_exam::find($id);
                $tbl->status=$form['status'];
                $tbl->save();

                return redirect('/admin/exams/'.$id.'/show');
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

    public function results($id,$student)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                \session()->put('exam_id',$id);
                $data=course_exam_answer::where('course_exam_id',$id)->where('student_id',$student)->get();
                return view('admin.exams_results',compact('data'));
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

    public function exam_quests_filter($id, Request $request)
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
                        $data=question::orderBy('title','asc')->paginate(5);
                    }
                    else
                    {

                        $data=question::where('title','LIKE','%'.$search.'%')->orderBy('title','asc')->paginate(5);
                    }
                }
                else
                {
                    $data=question::where('group_id',$drop)->orderBy('title','asc')->paginate(5);
                }

                \session()->put('exam_id',$id);
                $questions=course_exam_question::where('course_exam_id',$id)->get();
                return  view('admin.exams_questions',compact('data','questions'));
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
    public function exam_quests($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                \session()->put('exam_id',$id);
                $data=question::orderby('group_id','asc')->get();
                $questions=course_exam_question::where('course_exam_id',$id)->get();
                return  view('admin.exams_questions',compact('data','questions'));
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

    public function question_add($id,Request $request)
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
                    $question_id= $f[0];
                    $data=course_exam_question::where('course_exam_id',$id)->where('question_id',$question_id)->get();
                    if(count($data)==0)
                    {
                        $tbl=new course_exam_question();
                        $tbl->course_exam_id=$id;
                        $tbl->question_id=$question_id;
                        $tbl->save();
                    }
                }


                return redirect('/admin/exams/'.$id.'/questions');
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


    public function question_del($id,$question)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                course_exam_question::where('course_exam_id',$id)->where('id',$question)->delete();
                return redirect('/admin/exams/'.$id.'/questions');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(\session()->get('level')==0)
        {
            $courses=course::all();
            $data=course_exam::find($id);
            return view('admin.exams_edit',compact('data','courses'));
        }
        elseif(\session()->get('level')==1)
        {
            return  redirect('/teacher');
        }
        elseif(\session()->get('level')==2)
        {
            return  redirect('/student');
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
                    'course'=>'required |Numeric',
                    'start_date'=>'required',
                    'from_time'=>'required',
                    'to_time'=>'required',
                ]);

                $form=$request->all();

                $tbl=course_exam::find($id);
                $tbl->title=$form['name'];
                $tbl->course_id=$form['course'];
                $tbl->type=0;
                $tbl->text=$form['text'];
                $tbl->start_date=$form['start_date'];
                $tbl->from_time=$form['from_time'];
                $tbl->to_time=$form['to_time'];
                $tbl->total=0;
                $tbl->save();


                return redirect('/admin/exams');
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
                course_exam::find($id)->delete();
                course_exam_student::where('course_exam_id',$id)->delete();
                course_exam_answer::where('course_exam_id',$id)->delete();
                course_exam_question::where('course_exam_id',$id)->delete();

                return redirect('/admin/exams');

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
