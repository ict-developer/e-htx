<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tblmenu;
use App\Tbltype;
use App\Tblgroupmenu;
use App\Tblgroup_new;
use App\Tblgroup_nongsan;
use App\Tblgroupquestionanswer;
use App\Tblgrouploaihinh;
use App\Tbldisctrict_coquanchuquan;
use App\Tblcooperactive_hoptacxa;
use App\Tblcommunity_phong;
use Carbon\Carbon;
use Auth;
use Input;

class GroupLoaiHinhControl extends Controller
{
    public function getGroupLoaiHinh_list()
    {        
        $list = Tblgrouploaihinh::select('tblgrouploaihinh.*','tbltype.NAME_TYPE')
        ->join('tbltype','tbltype.id','=','tblgrouploaihinh.ID_TYPE')
        ->orderBy('id','desc')->paginate(10);
        return view('admin.mains.groups.group_loaihinhs',['list'=> $list]);
    }
    /**
     * Show the form for creating a new resosurce.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGroupLoaiHinh_Create()
    {	
        $list_one=Tbldisctrict_coquanchuquan::select('tbldisctrict_coquanchuquan.*')->orderBy('id','desc')->get();
        $list_two=Tbltype::select('tbltype.*')->where("TBL_ID_TYPE",0)->orderBy('id','desc')->get();
        return view('admin.mains.groups.group_loaihinhs_create',['list_one'=>$list_one,'list_two'=>$list_two]);
    }
    public function postGroupLoaiHinh_Create(Request $request)
    {
    	$date=Carbon::now()->format('Y-m-d');
       //  $date=Carbon::now()->format('Y-m-d H:m:s');  
        $this->validate($request,[
            'txtTenLoai'=>'required',
            'opLoai_T'=>'required',
            'opLoai_Two'=>'required'
            ],[
            'txtTenLoai.required' => 'Tên loại hình sản xuất không được để rỗng',
            'opLoai_T.required' => 'Tên loại không được để rỗng',
            'opLoai_Two.required' => 'Tên hợp tác xã không được rỗng'
            ]);
        $list = new Tblgrouploaihinh;
        $list->NAME_LOAIHINH = $request->txtTenLoai;
        $list->ID_HOPTACXA = $request->opLoai_T;
        $list->ID_TYPE = $request->opLoai_Two;
        $list->NO_NAME_LOAIHINH = changeTitle($request->txtTenLoai);
        $list->DESCRIPTION_LOAIHINH = $request->txtDescription;
        $list->FLAG_LOAIHINH = $request->opFlag;
        $sel=Tblgrouploaihinh::select("tblgrouploaihinh.*")->where("ID_HOPTACXA",$request->opLoai_T)->where("ID_TYPE",$request->opLoai_Two)->get();
       	if(count($sel)>0)
       	{
       		return redirect('mains/group_loai_hinhs_create.html')->with('info','Đã tồn tại dữ liệu');
       	}
       	else
       	{
       		$list->save();
       		return redirect('mains/group_loai_hinhs.html')->with('info','Đã thêm dữ liệu thành công');
       	}      
    }
    public function getCheckOption_XaPhong(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            echo"<option value=''>--Chọn--</option>"; 
            $info = Tblcommunity_phong::where('ID_DISTRICT','=',$id)->get();
            foreach($info as $x)
            {
                echo"<option value='".$x->id."'>".$x->NAME_COMMUNITY."</option>"; 
            }
        }
    }
    public function getCheckOption_HopTacXa(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            echo"<option value=''>--Chọn--</option>"; 
            $info = Tblcooperactive_hoptacxa::where('ID_COMMUNITY','=',$id)->get();
            foreach($info as $x)
            {
                echo"<option value='".$x->id."'>".$x->NAME_HOPTACXA."</option>"; 
            }
        }
    }
    public function getGroupLoaiHinh_Edit($id)
    {
    	$list = Tblgroupquestionanswer::find($id);
    	$list_one=Tblmenu::select('tblmenu.*')->where("TBL_ID_MENU",0)->orderBy('id','desc')->get();
        $list_two=Tbltype::select('tbltype.*')->where("TBL_ID_TYPE",0)->orderBy('id','desc')->get();
        return view('admin.mains.groups.group_loaihinhs_edit',['list_one'=>$list_one,'list_two'=>$list_two,'list'=>$list]);
    }
    public function postGroupLoaiHinh_Edit(Request $request,$id)
    {
    	$date=Carbon::now()->format('Y-m-d');
       //  $date=Carbon::now()->format('Y-m-d H:m:s');  
        $this->validate($request,[
            'txtTenLoai'=>'required',
            'opLoai_Two'=>'required'
            ],[
            'txtTenLoai.required' => 'Tên nhóm tin tức không được để rỗng',
            'opLoai_Two.required' => 'Tên loại không được để rỗng'
            ]);
        $list = Tblgrouploaihinh::find($id);
        $list->NAME_LOAIHINH = $request->txtTenLoai;
        $list->ID_HOPTACXA = $request->opLoai_T;
        $list->ID_TYPE = $request->opLoai_Two;
        $list->NO_NAME_LOAIHINH = changeTitle($request->txtTenLoai);
        $list->DESCRIPTION_LOAIHINH = $request->txtDescription;
        $list->FLAG_LOAIHINH = $request->opFlag;
        $list->save();
       	return redirect('mains/group_loai_hinhs.html')->with('info','Đã cập nhật dữ liệu thành công'); 
    }
    public function getGroupLoaiHinh_Clock($id,$id_)
    {
        $list_i = Tblgrouploaihinh::find($id);
        if($id_==0)
        {
        	$list_i->FLAG_LOAIHINH = 1;
        }
        else
        {
        	$list_i->FLAG_LOAIHINH = 0;
        }
        $list_i->save();
        return redirect('mains/group_loai_hinhs.html')->with('info','Đã khóa dữ liệu thành công');
    }
    public function getGroupLoaiHinh_Clock_Open($id,$id_)
    {
    	$list_i = Tblgrouploaihinh::find($id);
        if($id_==0)
        {
        	$list_i->FLAG_LOAIHINH = 1;
        }
        else
        {
        	$list_i->FLAG_LOAIHINH = 0;
        }
        $list_i->save();
        return redirect('mains/group_loai_hinhs.html')->with('info','Đã mở dữ liệu thành công');
    }
    public function getGroupLoaiHinh_Del($id)
    {
        $list = Tblgrouploaihinh::find($id);
        $list->delete();
            return redirect('mains/group_loai_hinhs.html')->with('info','Xóa dữ liệu thành công.');
        // if(count($list) > 0 )
        // {
        //     $list->delete();
        //     return redirect('mains/loai_types.html')->with('info','Xóa dữ liệu thành công.');
        // }else {
        //    return redirect('mains/loai_types.html')->with('info','Lỗi, thực hiện không được liên quan khóa ngoại.');
        // }    
    }       
}
