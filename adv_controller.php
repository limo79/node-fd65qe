<?php

namespace App\Http\Controllers;

use App\Models\adv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class adv_controller extends Controller
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
                $data=adv::orderbyraw('register_date desc')->paginate(20);
                return view('admin.advs',compact('data'));
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
                $data=adv::find($id);
                if($data->show==0) $data->show=1;
                else $data->show=0;

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
                return view('admin.advs_add');
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
                    'end_date'=>'required',
                ]);
                $form=$request->all();

                 $filename='noPic.jpg';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }



                include (storage_path() ."/jdf.php");
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                $tbl=new adv();
                $tbl->name=$form['name'];
                $tbl->register_date=$date;
                $tbl->expire_date=$form['end_date'];
               // $tbl->text=$form['text'];
                $tbl->link=$form['link'];
                if($filename!="")$tbl->pic=$filename;
                $tbl->save();

                Session::flash('message', '?????????????? ???? ???????????? ?????? ??????????');
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
                        $data=adv::orderBy('name','asc')->paginate(5);
                    }
                    else
                    {

                        $data=adv::where('name','LIKE','%'.$search.'%')->orderBy('name','asc')->paginate(5);
                    }
                }
                else
                {
                    $data=adv::where('show',$drop)->orderBy('name','asc')->paginate(5);
                }

                return view('admin.advs',compact('data'));
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
                $data= adv::find($id);
                return view('admin.advs_edit',compact('data'));
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
                    'end_date'=>'required',
                ]);
                $form=$request->all();

                   $filename='';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }



                include (storage_path() ."/jdf.php");
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                $tbl=adv::find($id);
                $tbl->name=$form['name'];
                $tbl->expire_date=$form['end_date'];
              //  $tbl->text=$form['text'];
                $tbl->link=$form['link'];
                if($filename!="")$tbl->pic=$filename;
                $tbl->save();

                Session::flash('message', '?????????????? ???? ???????????? ?????? ??????????');
                Session::flash('alert-class', 'alert-success');

                return redirect("/admin/advs");
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
                adv::find($id)->delete();
                return redirect('/admin/advs');
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
