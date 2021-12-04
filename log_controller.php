<?php

namespace App\Http\Controllers;

use App\Models\log;
use Illuminate\Http\Request;

class log_controller extends Controller
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
                $data=log::orderbyraw('date desc,time desc')->paginate(10);
                return view('admin.logs',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                $account=session()->get('id');
                $data=log::where('account_id',$account)->orderbyraw('date desc,time desc')->paginate(10);
                return view('teacher.logs',compact('data'));
            }
            elseif(\session()->get('level')==2)
            {
                $account=session()->get('id');
                $data=log::where('account_id',$account)->orderbyraw('date desc,time desc')->paginate(10);
                return view('student.logs',compact('data'));
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
                        $data=log::orderByRaw('date desc,time desc')->paginate(5);
                    }
                    else
                    {
                        $data=log::join('accounts','logs.account_id','accounts.id')->where('name','LIKE','%'.$search.'%')->orWhere('date','LIKE','%'.$search.'%')->paginate(10);

                    }
                }
                else
                {
                    $data=log::where('type',$drop)->orderByRaw('date desc,time desc')->paginate(5);
                }

                return view('admin.logs',compact('data'));
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
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                log::find($id)->delete();
                return redirect('/admin/logs');
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
