<?php

namespace App\Http\Controllers;

use App\Models\course_title_content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class content_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id,$url)
    {
        if(session()->has('id'))
        {
            session()->put('title_id',$id);
            if(\session()->get('level')==0)
            {
                $data=course_title_content::where('course_title_id',$id)->get();
                return view('admin.contents',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                $data=course_title_content::where('course_title_id',$id)->get();
                return view('teacher.contents',compact('data'));
            }
            elseif(\session()->get('level')==2)
            {
                $account=\session()->get('id');
                if(session()->has('allow'))
                {
                    if(\session()->get('allow')==0)
                    {
                        return  redirect('/student');
                    }
                    else
                    {
                        $data=course_title_content::where('course_title_id',$id)->get();
                        return view('student.contents',compact('data'));
                    }
                }
                else
                {
                    return  redirect('/student');
                }
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
                return view('admin.contents_add');
            }
            elseif(\session()->get('level')==1)
            {
                return view('teacher.contents_add');
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
    public function store(Request $request,$id,$course)
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

                $filename='';
                $ext='';
                if(\request()->file!="")
                {
                    $ext=request()->file->getClientOriginalExtension();
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }


                $tbl=new course_title_content();
                $tbl->title=$form['title'];
                $tbl->course_title_id=$id;
                $tbl->text=$form['editor1'];
                if($filename!="")
                {
                    $tbl->file=$filename;
                    $tbl->ext=$ext;
                }

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

                $filename='';
                $ext='';
                if(\request()->file!="")
                {
                    $ext=request()->file->getClientOriginalExtension();
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }


                $tbl=new course_title_content();
                $tbl->title=$form['title'];
                $tbl->course_title_id=$id;
                $tbl->text=$form['editor1'];
                if($filename!="")
                {
                    $tbl->file=$filename;
                    $tbl->ext=$ext;
                }

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
    public function edit($id,$title)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=course_title_content::find($id);
                return view('admin.contents_edit',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                $data=course_title_content::find($id);
                return view('teacher.contents_edit',compact('data'));
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
    public function update(Request $request, $id,$title)
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

                $filename='';
                $ext='';
                if(\request()->file!="")
                {
                    $ext=request()->file->getClientOriginalExtension();
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }


                $tbl=course_title_content::find($id);
                $tbl->title=$form['title'];
                $tbl->text=$form['editor1'];
                if($filename!="")
                {
                    $tbl->file=$filename;
                    $tbl->ext=$ext;
                }

                $tbl->save();

                return redirect('/admin/contents/'.$title.'/'.\session()->get('course_id'));
            }
            elseif(\session()->get('level')==1)
            {
                $request->validate([
                    'title'=>'required',
                    'editor1'=>'required',
                ]);

                $form=$request->all();

                $filename='';
                $ext='';
                if(\request()->file!="")
                {
                    $ext=request()->file->getClientOriginalExtension();
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }


                $tbl=course_title_content::find($id);
                $tbl->title=$form['title'];
                $tbl->text=$form['editor1'];
                if($filename!="")
                {
                    $tbl->file=$filename;
                    $tbl->ext=$ext;
                }

                $tbl->save();

                return redirect('/teacher/contents/'.$title.'/'.\session()->get('course_id'));
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
    public function destroy($id,$title)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                course_title_content::find($id)->delete();
                return redirect('/admin/contents/'.$title.'/'.\session()->get('course_id'));
            }
            elseif(\session()->get('level')==1)
            {
                course_title_content::find($id)->delete();
                return redirect('/teacher/contents/'.$title.'/'.\session()->get('course_id'));
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
