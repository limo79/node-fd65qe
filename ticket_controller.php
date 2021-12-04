<?php

namespace App\Http\Controllers;

use App\Models\about;
use App\Models\student;
use App\Models\ticket;
use Illuminate\Http\Request;

class ticket_controller extends Controller
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
                $data=ticket::orderByRaw('date desc,time desc')->paginate(10);
                return  view('admin.tickets',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                $account=session()->get('id');
                $data=ticket::where('account_id',$account)->orderByRaw('date desc,time desc')->paginate(10);
                return  view('student.tickets',compact('data'));
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
                return  redirect('/admin');
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                return view('student.tickets_add');
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
                return  redirect('/admin');
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                $request->validate([
                    'title'=>'required',
                    'editor1'=>'required',
                ]);
                $form=$request->all();


                   $filename='';
                if(\request()->file!="")
                {
                    $filename = time().'.'.request()->file->getClientOriginalExtension();
                    request()->file->move(public_path('uploads'), $filename);
                }



                include (storage_path()."/jdf.php");
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                $id=rand(11111111,99999999);
                $account=\session()->get('id');

                $tbl=new ticket();
                $tbl->id=$id;
                $tbl->title=$form['title'];
                $tbl->account_id=$account;
                $tbl->date=$date;
                $tbl->time=$time;
                $tbl->text=$form['editor1'];
                $tbl->attach=$filename;
                $tbl->status=0;
                $tbl->save();

                return redirect('/student/tickets');
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
                        $data=ticket::orderByRaw('date desc,time desc')->paginate(5);
                    }
                    else
                    {
                        $fields=['tickets.id','account_id','name','date','time','title','text','attach','status','answer'];
                        $data=ticket::join('students','account_id','students.id')->where('title','LIKE','%'.$search.'%')->orWhere('students.name','LIKE','%'.$search.'%')->orwhere('date','LIKE','%'.$search.'%')->orwhere('text','LIKE','%'.$search.'%')->orderByRaw('date desc,time desc')->paginate(10,$fields);
                    }
                }
                else
                {

                    $data=ticket::where('status',$drop)->orderByRaw('date desc,time desc')->paginate(5);
                }
                return view('admin.tickets',compact('data'));
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


    public function reply($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=ticket::findOrFail($id);
                return view('admin.tickets_reply',compact('data'));
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
    public function save(Request $request,$id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $form=$request->all();
                $tbl=ticket::findOrFail($id);

                $student=student::where('id',$tbl->account_id)->first();
                $mobile=$student->mobile;
                $about=about::first();


                $tbl->status=1;
                if($form['text']!="")
                    $tbl->reply=$form['text'];
                else
                    $tbl->reply=$form['answer'];
                $tbl->save();


                // Smsirlaravel::send( $student->name ." گرامی، تیکت شما پاسخ داده شد. ". $about->name,$mobile);

                return redirect('/admin/'.session()->get('addr'));
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
        //
    }
}
