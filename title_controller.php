<?php

namespace App\Http\Controllers;

use App\Models\contact;
use App\Models\course_title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class title_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                \session()->put('course_id',$id);
                return view('admin.titles_add');
            }
            elseif(\session()->get('level')==1)
            {
                \session()->put('course_id',$id);
                return view('teacher.titles_add');
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
    public function store(Request $request,$id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'title'=>'required',
                    'editor1'=>'required',
                ]);

                $form=$request->all();

                $tbl=new course_title();
                $tbl->title=$form['title'];
                $tbl->course_id=$id;
                $tbl->text=$form['editor1'];
                $tbl->save();

                Session::flash('message', 'اطلاعات با موفقیت ثبت گردید');
                Session::flash('alert-class', 'alert-success');

                return back();
            }
            elseif(\session()->get('level')==1)
            {
                $request->validate([
                    'title'=>'required',
                    'editor1'=>'required',
                ]);

                $form=$request->all();

                $tbl=new course_title();
                $tbl->title=$form['title'];
                $tbl->course_id=$id;
                $tbl->text=$form['editor1'];
                $tbl->save();

                Session::flash('message', 'اطلاعات با موفقیت ثبت گردید');
                Session::flash('alert-class', 'alert-success');

                return back();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$course)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=course_title::find($id);
                return view('admin.titles_edit',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                $data=course_title::find($id);
                return view('teacher.titles_edit',compact('data'));
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
    public function update(Request $request, $id,$course)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'title'=>'required',
                    'editor1'=>'required',
                ]);

                $form=$request->all();

                $tbl=course_title::find($id);
                $tbl->title=$form['title'];
                $tbl->course_id=$course;
                $tbl->text=$form['editor1'];
                $tbl->save();


                return redirect("/admin/courses/".$course."/show");
            }
            elseif(\session()->get('level')==1)
            {
                $request->validate([
                    'title'=>'required',
                    'editor1'=>'required',
                ]);

                $form=$request->all();

                $tbl=course_title::find($id);
                $tbl->title=$form['title'];
                $tbl->course_id=$course;
                $tbl->text=$form['editor1'];
                $tbl->save();


                return redirect("/teacher/courses/".$course."/show");
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
    public function destroy($id,$course)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                course_title::find($id)->delete();

                return redirect("/admin/courses/".$course."/show");
            }
            elseif(\session()->get('level')==1)
            {
                course_title::find($id)->delete();

                return redirect("/teacher/courses/".$course."/show");
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
