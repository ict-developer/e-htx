<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Session;
use Carbon\Carbon;
use App\User;
use DB;
use App\Coquancha;
use App\Coquancon;
use App\Luongnhanvien;
use App\Nhanvien;
use App\Phucap;
use App\Baocao_4b;
use App\Caicachtienluong;
use App\Khuvuc;
use App\Linhvuccon;
use App\Hsbienche;
use App\Capcoquan;
use App\Luongcanban;
use App\Hschucvu;
use Illuminate\Support\Str;
use App\Hsluong;
use Input;
use Hash;
class AdminController extends Controller {
    
    public function getDangnhap()
      {
      	return view('dangnhap.login');
      }
    public function postDangnhap(Request $request)
              {
        $this->validate($request, [
          'tendangnhap' => 'required',
          'password' => 'required',
        ],[
        'tendangnhap.required' => ' Vui lòng nhập tên đăng nhập!',
        'password.required' => ' Vui lòng nhập mật khẩu!',
        ]);
        //echo  $request->password;
        //$creds  = array('tendangnhap'=>$request->tendangnhap, 'password' => $request->password,'donvi_id'=>0);
        $creds  = array('tendangnhap'=>$request->tendangnhap, 'password' => $request->password);
        $tendangnhap = $request->get('tendangnhap');
        if( Auth::attempt($creds))
          {

            $users= Auth::user();
            $request->session()->put('tendangnhap',$tendangnhap);
            if ($users->hasRole(array('admin')))
            {
              return redirect('users');      
            }
            elseif ($users->hasRole(array('quantrihtx')))
            {
              return redirect('20/25/htx-nong-lam-ngu-nghiep.html');  
            }  
        	}
        else
	        {
	              return redirect('login')->with('thongbao',"Đăng nhập không thành công");
	        }
    }
    public function getDangxuat()
    {
	    $users= Auth::user();
	    $user = User::find($users->id);
	    $user->save();
	   
	    Auth::logout();
	    return redirect('login');
    }
}