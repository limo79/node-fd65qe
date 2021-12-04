<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class question_controller extends Controller
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
                $data=question::orderByraw('group_id asc,title asc')->paginate(10);
                return view('admin.questions',compact('data'));
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
                $data=group::all();
                return view('admin.questions_add',compact('data'));
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
                    'title'=>'required',
                    'ans1'=>'required',
                    'ans2'=>'required',
                    'ans3'=>'required',
                    'ans4'=>'required',
                ]);

                $form=$request->all();
                $tbl=new question();
                $tbl->title=$form['title'];
                $tbl->group_id=$form['groups'];
                $tbl->ans1=$form['ans1'];
                $tbl->ans2=$form['ans2'];
                $tbl->ans3=$form['ans3'];
                $tbl->ans4=$form['ans4'];
                $tbl->true=$form['true'];
                $tbl->score=0;
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
    public function edit($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=question::find($id);
                $groups=group::all();
                return view('admin.questions_edit',compact('data','groups'));
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
                    'title'=>'required',
                    'ans1'=>'required',
                    'ans2'=>'required',
                    'ans3'=>'required',
                    'ans4'=>'required',
                ]);

                $form=$request->all();
                $tbl=question::find($id);
                $tbl->title=$form['title'];
                $tbl->group_id=$form['groups'];
                $tbl->ans1=$form['ans1'];
                $tbl->ans2=$form['ans2'];
                $tbl->ans3=$form['ans3'];
                $tbl->ans4=$form['ans4'];
                $tbl->true=$form['true'];
                $tbl->score=0;
                $tbl->save();


                return redirect('/admin/questions');
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
                question::find($id)->delete();
                return redirect('/admin/questions');
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
