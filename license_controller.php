<?php

namespace App\Http\Controllers;

use App\Models\service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class license_controller extends Controller
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
                $data=service::paginate(10);
                return view('admin.licenses',compact('data'));
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
                return view('admin.licenses_add');
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
                    'editor1'=>'required',
                ]);

                $form=$request->all();


                 $filename='noPic.jpg';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }




                $tbl=new service();
                $tbl->name=$form['name'];
                $tbl->text=$form['editor1'];
                $tbl->pic=$filename;
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
                $data=service::find($id);
                return view('admin.licenses_edit',compact('data'));
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
                    'editor1'=>'required',
                ]);


                $form=$request->all();


                   $filename='';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }




                $tbl= service::find($id);
                $tbl->name=$form['name'];
                $tbl->text=$form['editor1'];
                if($filename!="") $tbl->pic=$filename;
                $tbl->save();

                return redirect('/admin/licenses');
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
                service::find($id)->delete();
                return redirect('/admin/licenses');
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

