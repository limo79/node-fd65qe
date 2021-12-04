<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\student;
use App\Models\team;
use App\Models\team_chat;
use App\Models\team_member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class team_controller extends Controller
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
                $data=team::orderbyraw('register desc')->paginate(20);
                return view('admin.teams',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                $account=\session()->get('id');
                $data=team_member::where('student_id',$account)->where('block',0)->paginate(20);

                return view('student.teams',compact('data'));
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
                $data=team::find($id);
                if($data->active==0) $data->active=1;
                else $data->active=0;

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

    public function member_active($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=team_member::find($id);
                if($data->block==0) $data->block=1;
                else $data->block=0;

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

    public function members($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=team::where('id',$id)->get();
                $students=student::get();
                $members=team_member::where('team_id',$id)->get();
                return view('admin.teams_members',compact('data','students','members'));
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

    public function member_add ($id,$student)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $data=team_member::where('team_id',$id)->where('student_id',$student)->get();
                if(count($data)==0)
                {
                    $tbl=new team_member();
                    $tbl->team_id=$id;
                    $tbl->student_id=$student;
                    $tbl->save();
                }

                return redirect('/admin/teams/'.$id.'/members');
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

    public function member_del ($id,$student)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                team_member::where('team_id',$id)->where('student_id',$student)->delete();
                return redirect('/admin/teams/'.$id.'/members');
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
                return view('admin.teams_add');
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
                        $data=team::orderBy('name','asc')->paginate(5);
                    }
                    else
                    {

                        $data=team::where('name','LIKE','%'.$search.'%')->orderBy('name','asc')->paginate(5);
                    }
                }
                else
                {
                    $data=team::where('active',$drop)->orderBy('name','asc')->paginate(5);
                }

                return view('admin.teams',compact('data'));
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
    public function chat_add(Request $request,$id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'text'=>'required',
                ]);
                $form=$request->all();

                $account=\session()->get('id');
                include (storage_path() ."/jdf.php");
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                $tbl=new team_chat();
                $tbl->date=$date;
                $tbl->time=$time;
                $tbl->text=$form['text'];
                $tbl->team_id=$id;
                $tbl->account_id=$account;
                $tbl->save();

                return redirect('/admin/teams/'.$id.'/show');

            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                $request->validate([
                    'text'=>'required',
                ]);
                $form=$request->all();

                $account=\session()->get('id');
                include (storage_path() ."/jdf.php");
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                $tbl=new team_chat();
                $tbl->date=$date;
                $tbl->time=$time;
                $tbl->text=$form['text'];
                $tbl->team_id=$id;
                $tbl->account_id=$account;
                $tbl->save();

                return redirect('/student/teams/'.$id.'/show');
            }
        }
        else
        {
            return  redirect('/');
        }
    }

    public function store(Request $request)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'name'=>'required',
                ]);
                $form=$request->all();
                $exist=team::where('name',$form['name'])->first();
                if($exist!=null)
                {
                    Session::flash('message', 'اطلاعات وارد شده تکراری می باشد');
                    Session::flash('alert-class', 'alert-warning');

                    return back()->withInput();
                }

                include (storage_path() ."/jdf.php");
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');
                $id=rand(111111,999999);
                $tbl=new team();
                $tbl->id=$id;
                $tbl->name=$form['name'];
                $tbl->register=$date;
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
                $data=team::find($id);
                $students=team_member::where('team_id',$id)->get();
                return view('admin.teams_show',compact('data','students'));
            }
            elseif(\session()->get('level')==1)
            {
                return  redirect('/teacher');
            }
            elseif(\session()->get('level')==2)
            {
                $account=\session()->get('id');
                $data=team_member::where('team_id',$id)->where('student_id',$account)->get();
                if(count($data)==0)
                {
                    return  redirect('/student');
                }
                else
                {
                    $data = team::find($id);
                    return view('student.teams_show',compact('data'));
                }


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
                $data=team::find($id);
                return view('admin.teams_edit',compact('data'));
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
                ]);
                $form=$request->all();

                $tbl=team::find($id);
                $tbl->name=$form['name'];
                $tbl->save();

                return redirect('/admin/teams');
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
                team::find($id)->delete();

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
}
