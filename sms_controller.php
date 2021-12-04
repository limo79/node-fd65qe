<?php

namespace App\Http\Controllers;

use App\Models\about;
use App\Models\student;
use Illuminate\Http\Request;

class sms_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                $data=student::orderby('name','asc')->paginate(10);
                return view('admin.sms_add',compact('data'));
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
                    'text'=>'required',
                ]);


                $form=$request->all();

                $about=about::first();

                if($form['for']==0)
                {
                    $data=student::all();
                    foreach ($data as $d)
                    {
                        //Smsirlaravel::send( $form["text"].". ".$about->name,$d->mobile);
                    }
                }
                else
                {
                    foreach ($form['list'] as $l)
                    {
                        $data=customer::find($l);
                        // Smsirlaravel::send( $form["text"].". ".$about->name,$data->mobile);
                    }
                }

                Session::flash('alert', 'پیامک با موفقیت برای تمامی/لیست مدنظر ارسال گردید');
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
        //
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
        //
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
