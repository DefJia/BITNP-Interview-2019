<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
//use Subfission\Cas\CASManager;

class DBController extends Controller {

    function get_current_user(){
        if (Auth::user()){
            $name = Auth::user()->name;
            return $name;
        } else{
            return null;
        }
    }

    public function show_list($date_id, $room_id){
        $dates = array('10月17日', '10月18日', '10月19日');
        $rooms = array('2B-503', '2B-504');

        $status_code = array('没来', '候场', '准备出发', '面试中', '结束');
        $status_color = array('secondary', 'primary', 'danger', 'warning', 'success');

        $button_text = array('签到2B-503', '签到2B-504', '准备面试', '安排出发', '信息');
        $button_color = array('secondary', 'secondary', 'primary', 'danger', 'info');
        
        $room_id = (int)$room_id;
        $date_id = (int)$date_id;
        if($room_id == 10) $flag = 1; else $flag = 0;
        // 是否为候场教室
        if(!(0 <= $date_id && $date_id <= sizeof($dates)))
            $date_id = 0;
        if(!(0 <= $room_id && $room_id <= sizeof($rooms)))
            $room_id = 0;
        // 0为全部
        $html = '';
        $title = '';
        if($this->get_current_user()){
            if ($flag == 1){
                $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->where([['status', '>=', 1], ['status', '<=', 2]])->orderBy('time', 'asc')->get();
                $title = '候场教室';
            }else{
                if ($date_id == 0 && $room_id == 0){
                    // 全名单
                    $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->orderBy('date', 'asc')->get();
                    $title = '所有';
                } elseif($room_id == 0){
                    // 选日期未选教室
                        $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->where('date', $dates[$date_id-1])->orderBy('time', 'asc')->get();
                        $title = $dates[$date_id-1];
                }elseif($date_id == 0){
                    // 选教室未选日期
                    $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->where('room', $rooms[$room_id-1])->orderBy('time', 'asc')->get();
                    $title = $rooms[$room_id-1];
                }else{
                    $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->where([['room', $rooms[$room_id-1]], ['date', $dates[$date_id-1]]])->orderBy('time', 'asc')->get();
                    $title = $dates[$date_id-1].$rooms[$room_id-1];
                }
            }
            // 查询数据库
            foreach($records as $record){
                $html .= '<tr>';
                $cnt = 0;
                foreach ($record as $elem) {
                    $cnt++;
                    if ($cnt < 5){
                        $html .= sprintf('<td>%s</td>', $elem);
                    } elseif($cnt == 5){
                        $html .= sprintf('<td><button type="button" class="btn btn-%s">%s</button></td>', $status_color[$elem], $status_code[$elem]);
                    } elseif($cnt == 6){
                        for($i = 0; $i < 5; $i++){
                            $button = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), $i, $elem, $button_color[$i], $button_text[$i]);
                            if ($i == 0) $button = substr($button, 0, -5);
                            elseif ($i == 1) $button = substr($button, 4);
                            $html .= $button;
                        }
                    }
                }
            $html .= '</tr>';
            }
        } 
        $data = array($title, $html);
        return view('list')->with('data', $data);
    }

    public function show_info($id){
        $columns = array('序号', '姓名', '姓别', '电话号码', '校区', '学号', '专业/学院/书院', '第一志愿', '第二志愿', '第三志愿', '是否服从调剂', '自我介绍', '为什么加入网协', 'QQ号');
        
        $name = '';
        $comment_text = '';
        $detail_text = '';
        if($this->get_current_user()){
            // if(!is_integer($id)) return redirect('/');
            $id = (int)$id;
            $details = DB::table('info')->where('id', $id)->first();
            if(!$details) return redirect('/');
            $name = $details->name;
            // 获取名字
            $i = 0;
            foreach($details as $detail){
                $detail_text .= sprintf("<tr><td style='white-space:nowrap'>%s</td><td style='text-align:left'>%s</td></tr>", $columns[$i], $detail);
                $i++;
            }
            // 获取资料
            $comments = DB::table('cmt')->select('time', 'cmt', 'interviewer')->where('interviewee', $details->id)->get();
            foreach ($comments as $comment){
                $comment_text .= '<tr>';
                foreach ($comment as $item){
                    $comment_text .= sprintf("<td style='text-align:left'>%s</td>", $item);
                }
                $comment_text .= '</tr>';
            }
        }
        $data = [$name, $comment_text, $detail_text, $id];
        return view('info')->with('data', $data);
    }

    public function handle_action($aid, $uid){
        $rooms = array('2B-503', '2B-504');

        $admin = array('Defjia', '张正', 'testAdmin', '冯开宇', 'Thd');
        $interviewer = array(['Room1', 'PanYu', 'homer'], ['Room2', '谷旭凯', '宋尚儒', '刘宇']);
        $waiter = array('Waiter');
        $name = $this->get_current_user();

        if($aid == 0 || $aid == 1){
            // 签到 & 选教室
            if(in_array($name, $admin) || in_array($name, $waiter)){
                // 权限符合
                $status = DB::table('record')->select('status')->where('id', $uid)->get()[0];
                if ($status->status == 0){
                    DB::table('record')->where('id', $uid)->update(['status' => 1, 'room' => $rooms[$aid]]);
                    $message = $rooms[$aid].'签到成功！';
                } else{
                    $message = '签到失败！操作次序不当！';
                }
            } else{
                $message = '签到失败！无操作权限！';
            }
            return redirect(ENV('APP_URL').'/list/1/0')->with('message', $message);
            // return view('skip'))->with('message', $message);
        } elseif($aid == 2){
            // 准备面试
            $room_index = 0;
            if(in_array($name, $admin) || in_array($name, $interviewer[0]) || in_array($name, $interviewer[1])){
                // 权限符合
                $tmp = DB::table('record')->select('status', 'room')->where('id', $uid)->get()[0];
                $status = $tmp->status;
                $room = $tmp->room;
                $room_index = array_search($room, $rooms);
                if ($status == 1 && (in_array($name, $interviewer[$room_index]) || in_array($name, $admin))){
                    // 同一间教室
                    DB::table('record')->where('id', $uid)->update(['status' => 2]);
                    $message = '准备面试成功！';
                } else{
                    $message = '准备面试失败！操作次序不当或无操作权限！';
                }
            } else{
                $message = '准备面试失败！无操作权限！';
            }
            $room_index++;
            return redirect(ENV('APP_URL').'/list/1/'.$room_index)->with('message', $message);
        } elseif($aid == 3){
            // 前往面试
            if(in_array($name, $admin) || in_array($name, $waiter)){
                // 权限符合
                $status = DB::table('record')->select('status')->where('id', $uid)->get()[0];
                if ($status->status == 2){
                    DB::table('record')->where('id', $uid)->update(['status' => 3]);
                    $message = '安排出发成功！';
                } else{
                    $message = '安排出发失败！操作次序不当！';
                }
            } else{
                $message = '安排出发失败！无操作权限！';
            }
            return redirect(ENV('APP_URL').'/list/1/0')->with('message', $message);
        } elseif($aid == 4){
            // 个人信息
            return redirect(ENV('APP_URL').'/info/'.$uid);
        }
    }

    public function insert_data(){
        $name = $this->get_current_user();
        $cmt = Input::get('cmt');
        $eeid = Input::get('id');
        if ($cmt && $name)
            DB::table('cmt')->insert(['interviewee'=>$eeid, 'interviewer'=>$name, 'cmt'=>$cmt]);
            DB::table('record')->where('id', $eeid)->update(['status' => 4]);
        return redirect('/info/'.$eeid)->with('message', '面试评论提交成功！');
    }

    public function paris(){
        return redirect('/list/0/0')->with('message', '会场正在布置中！');
    }

}
