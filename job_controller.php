<?php

namespace App\Http\Controllers;

use App\Models\job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class job_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=job::orderbyraw('date desc,time desc')->paginate(20);
        return view('admin.jobs',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        $data=job::find($id);
        if($data->status==0) $data->status=1;
        else $data->status=0;

        $data->save();

        return back();
    }
    public function create()
    {
        return view('admin.jobs_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
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

        $tbl=new job();
        $tbl->title=$form['title'];
        $tbl->date=$date;
        $tbl->time=$time;
        $tbl->text=$form['text'];
        $tbl->status=1;
        if($filename!="")$tbl->file=$filename;
        $tbl->save();

        Session::flash('message', 'اطلاعات با موفقیت ثبت گردید');
        Session::flash('alert-class', 'alert-success');

        return back();
    }

    public function filter(Request $request)
    {
        $form= $request->all();
        $drop=$form['dropFilter'];
        $search=$form['txtSearch'];

        if($drop==-1)
        {
            if($search=="")
            {
                $data=job::orderBy('title','asc')->paginate(5);
            }
            else
            {

                $data=job::where('title','LIKE','%'.$search.'%')->orderBy('title','asc')->paginate(5);
            }
        }
        else
        {
            $data=job::where('status',$drop)->orderBy('title','asc')->paginate(5);
        }

        return view('admin.jobs',compact('data'));
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
        $data= job::find($id);
        return view('admin.jobs_edit',compact('data'));
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
        $request->validate([
            'title'=>'required',
        ]);
        $form=$request->all();

           $filename='';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }




        $tbl=job::find($id);
        $tbl->title=$form['title'];
        $tbl->text=$form['text'];
        if($filename!="")$tbl->file=$filename;
        $tbl->save();

        return redirect("/admin/jobs");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        adv::find($id)->delete();
        return redirect('/admin/advs');
    }
}
