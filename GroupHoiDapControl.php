<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tblmenu;
use App\Tbltype;
use App\Tblgroupmenu;
use App\Tblgroup_new;
use App\Tblgroup_nongsan;
use App\Tblgroupquestionanswer;
use Auth;
use Input;
use Carbon\Carbon;
class GroupHoiDapControl extends Controller
{
    public function getGroupHoiDap_list()
    {        
        $list = Tblgroupquestionanswer::select('tblgroupquestionanswer.*','tbltype.NAME_TYPE')
        ->join('tbltype','tbltype.id','=','tblgroupquestionanswer.ID_TYPE')
        ->orderBy('id','desc')->paginate(10);
        return view('admin.mains.groups.group_questionanswers',['list'=> $list]);
    }
    /**
     * Show the form for creating a new resosurce.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGroupHoiDap_Create()
    {	
        $list_one=Tblmenu::select('tblmenu.*')->where("TBL_ID_MENU",0)->orderBy('id','desc')->get();
        $list_two=Tbltype::select('tbltype.*')->where("TBL_ID_TYPE",0)->orderBy('id','desc')->get();
        return view('admin.mains.groups.group_question_answers_create',['list_one'=>$list_one,'list_two'=>$list_two]);
    }
    public function postGroupHoiDap_Create(Request $request)
    {
    	$date=Carbon::now()->format('Y-m-d');
       //  $date=Carbon::now()->format('Y-m-d H:m:s');  
        $this->validate($request,[
            'txtTenLoai'=>'required',
            'opLoai_Two'=>'required'
            ],[
            'txtTenLoai.required' => 'Tên nhóm không được để rỗng',
            'opLoai_Two.required' => 'Tên loại không được để rỗng'
            ]);
        $list = new Tblgroupquestionanswer;
        $list->NAME_QUESTION = $request->txtTenLoai;
        $list->ID_TYPE = $request->opLoai_Two;
        $list->NO_NAME_QUESTION = changeTitle($request->txtTenLoai);
        $list->DESCRITION_QUESTION = $request->txtDescription;
        $list->DATE_QUESTIONS = $date;
        $list->FLAG_QUESTIONS = $request->opFlag;
        $sel=Tblgroupquestionanswer::select("tblgroupquestionanswer.*")->where("ID_TYPE",$request->opLoai_Two)->get();
       	if(count($sel)>0)
       	{
       		return redirect('mains/group_hoi_daps.html')->with('info','Đã tồn tại dữ liệu');
       	}
       	else
       	{
       		$list->save();
       		return redirect('mains/group_hoi_daps.html')->with('info','Đã thêm dữ liệu thành công');
       	}      
    }
    public function getGroupHoiDap_Edit($id)
    {
    	$list = Tblgroupquestionanswer::find($id);
    	$list_one=Tblmenu::select('tblmenu.*')->where("TBL_ID_MENU",0)->orderBy('id','desc')->get();
        $list_two=Tbltype::select('tbltype.*')->where("TBL_ID_TYPE",0)->orderBy('id','desc')->get();
        return view('admin.mains.groups.group_question_answers_edit',['list_one'=>$list_one,'list_two'=>$list_two,'list'=>$list]);
    }
    public function postGroupHoiDap_Edit(Request $request,$id)
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
        $list = Tblgroupquestionanswer::find($id);
        $list->NAME_QUESTION = $request->txtTenLoai;
        $list->ID_TYPE = $request->opLoai_Two;
        $list->NO_NAME_QUESTION = changeTitle($request->txtTenLoai);
        $list->DESCRITION_QUESTION = $request->txtDescription;
        $list->DATE_QUESTIONS = $date;
        $list->FLAG_QUESTIONS = $request->opFlag;
        $list->save();
       	return redirect('mains/group_hoi_daps.html')->with('info','Đã cập nhật dữ liệu thành công'); 
    }
    public function getGroupHoiDap_Clock($id,$id_)
    {
        $list_i = Tblgroupquestionanswer::find($id);
        if($id_==0)
        {
        	$list_i->FLAG_QUESTIONS = 1;
        }
        else
        {
        	$list_i->FLAG_QUESTIONS = 0;
        }
        $list_i->save();
        return redirect('mains/group_hoi_daps.html')->with('info','Đã khóa dữ liệu thành công');
    }
    public function getGroupHoiDap_Clock_Open($id,$id_)
    {
    	$list_i = Tblgroupquestionanswer::find($id);
        if($id_==0)
        {
        	$list_i->FLAG_QUESTIONS = 1;
        }
        else
        {
        	$list_i->FLAG_QUESTIONS = 0;
        }
        $list_i->save();
        return redirect('mains/group_hoi_daps.html')->with('info','Đã mở dữ liệu thành công');
    }
    public function getGroupHoiDap_Del($id)
    {
        $list = Tblgroupquestionanswer::find($id);
        $list->delete();
            return redirect('mains/group_hoi_daps.html')->with('info','Xóa dữ liệu thành công.');
        // if(count($list) > 0 )
        // {
        //     $list->delete();
        //     return redirect('mains/loai_types.html')->with('info','Xóa dữ liệu thành công.');
        // }else {
        //    return redirect('mains/loai_types.html')->with('info','Lỗi, thực hiện không được liên quan khóa ngoại.');
        // }    
    }       
}
