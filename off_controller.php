<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\course_offer;
use App\Models\group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class off_controller extends Controller
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
                $data=course_offer::orderbyraw('end_date desc,start_date desc')->paginate(10);

                return view('admin.offers',compact('data'));
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
    public function active($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=course_offer::find($id);
                if($data->status==0) $data->status=1;
                else $data->status=0;

                $data->save();

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
    public function create()
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $courses=course::where('status',0)->get();
                return view('admin.offers_add',compact('courses'));
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
                    'courses'=>'required |Numeric',
                    'percent'=>'required | Numeric',
                    'start_date'=>'required',
                    'end_date'=>'required',
                ]);
                $form=$request->all();


                $id=rand(11111111,99999999);
                $tbl=new course_offer();
                $tbl->id=$id;
                $tbl->title=$form['name'];
                $tbl->text=$form['text'];
                $tbl->start_date=$form['start_date'];
                $tbl->end_date=$form['end_date'];
                $tbl->course_id=$form['courses'];
                $tbl->percent=$form['percent'];
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


    public function filter(Request $request)
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
                        $data=course_offer::orderBy('title','asc')->paginate(5);
                    }
                    else
                    {

                        $data=course_offer::where('title','LIKE','%'.$search.'%')->orderBy('title','asc')->paginate(5);
                    }
                }
                else
                {
                    $data=course_offer::where('status',$drop)->orderBy('title','asc')->paginate(5);
                }

                return view('admin.offers',compact('data'));
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
                $courses=course::where('status',0)->get();
                $data=course_offer::find($id);
                return view('admin.offers_edit',compact('data','courses'));
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
                    'courses'=>'required |Numeric',
                    'percent'=>'required | Numeric',
                    'start_date'=>'required',
                    'end_date'=>'required',
                ]);
                $form=$request->all();


                $tbl=course_offer::find($id);
                $tbl->title=$form['name'];
                $tbl->text=$form['text'];
                $tbl->start_date=$form['start_date'];
                $tbl->end_date=$form['end_date'];
                $tbl->course_id=$form['courses'];
                $tbl->percent=$form['percent'];
                $tbl->save();

                return redirect('/admin/offers');
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
                $data=course_offer::find($id)->delete();
                return redirect('/admin/offers');

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
