<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\course_student;
use App\Models\log;
use App\Models\student;
use App\Models\ticket;
use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class student_controller extends Controller
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
                $data=student::orderbyraw('register desc,name asc')->paginate(10);
                return view('admin.students',compact('data'));
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
                return view('admin.students_add');
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
                    'father'=>'required',
                    'national'=>'required|numeric',
                    'birthday'=>'required',
                    'mobile'=>'required|numeric',
                    'address'=>'required',
                ]);


                $form=$request->all();

                $exist=student::where('national',$form['national'])->orWhere('mobile',$form['mobile'])->first();
                if($exist!=null)
                {
                    Session::flash('message', 'اطلاعات وارد شده تکراری می باشد');
                    Session::flash('alert-class', 'alert-warning');

                    return back()->withInput();
                }

                 $filename='noPic.jpg';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }



                include(storage_path().'/jdf.php');
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                $id=rand(11111111,99999999);
                $tbl=new student([
                    'id'=>$id,
                    'name'=>$form['name'],
                    'national'=>$form['national'],
                    'father'=>$form['father'],
                    'mobile'=>$form['mobile'],
                    'birthday'=>$form['birthday'],
                    'email'=>$form['email'],
                    'register'=>$date,
                    'address'=>$form['address'],
                    'pic'=>$filename,
                ]);

                $tbl->save();



                $tbl=new account();
                $tbl->id=$id;
                $tbl->account_id=$id;
                $tbl->name=$form['name'];
                $tbl->username=$form['national'];
                $tbl->password=$form['national'];
                $tbl->mobile=$form['mobile'];
                $tbl->level=2;
                $tbl->block=false;
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

    public function filter(Request $request)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $form= $request->all();
                $search=$form['txtSearch'];
                if($search=="")
                {
                    $data=student::orderByRaw('name','asc')->paginate(5);
                }
                else
                {

                    $data=student::where('name','LIKE','%'.$search.'%')->orWhere('mobile','LIKE','%'.$search.'%')->orwhere('national','LIKE','%'.$search.'%')->orderByRaw('name','asc')->paginate(5);
                }
                return view('admin.students',compact('data'));
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
            if(\session()->get('level')==0)
            {
                $data=student::find($id);
                $logs=log::where('account_id',$id)->orderbyraw('date desc,time desc')->get();
                $tickets=ticket::where('account_id',$id)->orderbyraw('date desc,time desc')->get();
                $transactions=transaction::where('account_id',$id)->orderbyraw('date desc,time desc')->get();

                return view('admin.students_show',compact('data','logs','tickets','transactions'));
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
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=student::find($id);
                return view('admin.students_edit',compact('data'));
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
                    'father'=>'required',
                    'national'=>'required|numeric',
                    'birthday'=>'required',
                    'mobile'=>'required|numeric',
                    'address'=>'required',
                ]);


                $form=$request->all();

                   $filename='';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }



                include(storage_path().'/jdf.php');
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                $tbl=student::find($id);
                $tbl->name=$form['name'];
                $tbl->national=$form['national'];
                $tbl->mobile=$form['mobile'];
                $tbl->birthday=$form['birthday'];
                $tbl->email=$form['email'];
                $tbl->father=$form['father'];
                $tbl->address=$form['address'];
                if($filename!="")$tbl->pic=$filename;
                $tbl->save();


                $tbl=account::find($id);
                $tbl->name=$form['name'];
                $tbl->mobile=$form['mobile'];
                $tbl->save();

                return redirect("/admin/students");
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
                $data=course_student::where('student_id',$id)->get();
                if(count($data)==0)
                {
                   student::find($id)->delete();
                   account::find($id)->delete();
                   log::where('account_id',$id)->delete();

                    return redirect('/admin/students');
                }

                return redirect('/admin/students');
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
