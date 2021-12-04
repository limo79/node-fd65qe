<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\group;
use App\Models\log;
use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class teacher_controller extends Controller
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
                $data=teacher::orderbyraw('register desc,name asc')->paginate(10);
                return view('admin.teachers',compact('data'));
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
                return view('admin.teachers_add');
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
                    'national'=>'required|numeric',
                    'birthday'=>'required',
                    'mobile'=>'required|numeric',
                    'address'=>'required',
                ]);


                $form=$request->all();

                $exist=teacher::where('national',$form['national'])->orWhere('mobile',$form['mobile'])->first();
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
                $tbl=new teacher([
                    'id'=>$id,
                    'name'=>$form['name'],
                    'national'=>$form['national'],
                    'mobile'=>$form['mobile'],
                    'birthday'=>$form['birthday'],
                    'email'=>$form['email'],
                    'register'=>$date,
                    'cv'=>$form['cv'],
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
                $tbl->level=1;
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
                $data=teacher::find($id);
                $logs=log::where('account_id',$id)->orderbyraw('date desc,time desc')->get();

                return view('admin.teachers_show',compact('data','logs'));
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
                    $data=teacher::orderbyraw('register desc,name asc')->paginate(5);
                }
                else
                {

                    $data=teacher::where('name','LIKE','%'.$search.'%')->orWhere('mobile','LIKE','%'.$search.'%')->orwhere('national','LIKE','%'.$search.'%')->orwhere('address','LIKE','%'.$search.'%')->orderByRaw('name','asc')->paginate(5);
                }

                return view('admin.teachers',compact('data'));
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
                $data=teacher::find($id);
                return view('admin.teachers_edit',compact('data'));
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


                $tbl=teacher::find($id);
                $tbl->name=$form['name'];
                $tbl->national=$form['national'];
                $tbl->mobile=$form['mobile'];
                $tbl->birthday=$form['birthday'];
                $tbl->email=$form['email'];
                $tbl->cv=$form['cv'];
                $tbl->address=$form['address'];
                if($filename!="")$tbl->pic=$filename;
                $tbl->save();

                $tbl=account::find($id);
                $tbl->name=$form['name'];
                $tbl->mobile=$form['mobile'];
                $tbl->save();


                return redirect('/admin/teachers');
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
        //
    }
}
