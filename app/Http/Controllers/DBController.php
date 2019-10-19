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
        $rooms = array('2A-202', '2A-203');
        $departments = array('技术类', '电脑诊所', '数字媒体部', '组织部');

        // $status_code = array('没来', '候场', '准备出发', '面试中', '结束');
        // $status_color = array('secondary', 'primary', 'danger', 'warning', 'success');
        $status_code = array('没来', '候场', '准备出发', '面试中', '等待第一志愿录取', '等待第二志愿录取', '等待第三志愿录取', '等待捡漏/淘汰', '技术类录取', '电脑诊所录取', '数字媒体部录取', '组织部录取');
        $status_color = array('secondary', 'primary', 'danger', 'warning', 'primary', 'warning', 'dark', 'danger', 'success', 'success', 'success');

        $button_text = array('签到2A-202', '签到2A-203', '准备面试', '安排出发', '信息');
        $button_color = array('secondary', 'secondary', 'primary', 'danger', 'info');
        
        $room_id = (int)$room_id;
        $date_id = (int)$date_id;
        $flag = 0;
        if($room_id >= 5 && $room_id <= 14) $flag = 1; 
        elseif(!(0 <= $room_id && $room_id <= sizeof($rooms)))
            $room_id = 0;
        if(!(0 <= $date_id && $date_id <= sizeof($dates)))
            $date_id = 0;
        // 0为全部
        $html = '';
        $title = '';
        if($this->get_current_user()){
            if ($flag == 1){
                $titles = array('技术类录取队列', '电脑诊所录取队列', '数字媒体部录取队列', '录取队列', '捡漏列表', '候场教室', '技术类录取名单', '电脑诊所录取名单', '数字媒体部录取名单', '组织部录取名单');
                $title = $titles[$room_id-5];
                if($room_id == 10){
                    // 候场
                    $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->where([['status', '>=', 1], ['status', '<=', 3]])->orderBy('status', 'desc')->get();
                } elseif($room_id == 9){
                    // 捡漏, 录取
                    $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->where('status', 7)->get();
                }elseif($room_id < 9){
                    // 各部门待处理, 录取, 不录取
                    $records1 = DB::table('record')->join('info', 'record.id', '=', 'info.id')->select('record.date', 'record.room', 'record.time', 'record.name', 'record.status', 'record.id')->where([['record.status', 4], ['info.first', $departments[$room_id-5]]])->get();
                    $records2 = DB::table('record')->join('info', 'record.id', '=', 'info.id')->select('record.date', 'record.room', 'record.time', 'record.name', 'record.status', 'record.id')->where([['record.status', 5], ['info.second', $departments[$room_id-5]]])->get();
                    $records3 = DB::table('record')->join('info', 'record.id', '=', 'info.id')->select('record.date', 'record.room', 'record.time', 'record.name', 'record.status', 'record.id')->where([['record.status', 6], ['info.third', $departments[$room_id-5]]])->get();
                } elseif($room_id > 10){
                    // 各部门已录取, 删除
                    $records = DB::table('record')->select('date', 'room', 'time', 'name', 'status', 'id')->where('status', $room_id-3)->orderBy('status', 'desc')->get();
                }
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
            if ($room_id < 5 || $room_id > 8){
                foreach($records as $record){
                    $html .= '<tr>';
                    $cnt = 0;
                    foreach ($record as $elem) {
                        $cnt++;
                        if ($cnt < 5){
                            $html .= sprintf('<td>%s</td>', $elem);
                        } elseif($cnt == 5){
                            // if($elem > 4) $elem = 4;
                            $html .= sprintf('<td><button type="button" class="btn btn-%s">%s</button></td>', $status_color[$elem], $status_code[$elem]);
                        } elseif($cnt == 6){
                            if($flag == 0 || $room_id == 10){
                                // 面试
                                for($i = 0; $i < 5; $i++){
                                    $button = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), $i, $elem, $button_color[$i], $button_text[$i]);
                                    if ($i == 0) $button = substr($button, 0, -5);
                                    elseif ($i == 1) $button = substr($button, 4);
                                    $html .= $button;
                                }
                            } else{
                                // 录取
                                // 5-8 录取 不录取
                                // 9 录取
                                // 11-14 删除
                                if($room_id >= 5 && $room_id <= 8){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 6, $elem, 'danger', '不录取');
                                } elseif($room_id == 9){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = '';
                                } else{
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 7, $elem, 'danger', '删除');
                                    $button2 = '';
                                }
                                $button_info = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 4, $elem, $button_color[4], $button_text[4]);
                                $html .= $button_info.$button1.$button2;
                            }
                        }
                    }
                $html .= '</tr>';
                }
            } else{
                foreach($records1 as $record){
                    $html .= '<tr>';
                    $cnt = 0;
                    foreach ($record as $elem) {
                        $cnt++;
                        if ($cnt < 5){
                            $html .= sprintf('<td>%s</td>', $elem);
                        } elseif($cnt == 5){
                            // if($elem > 4) $elem = 4;
                            $html .= sprintf('<td><button type="button" class="btn btn-%s">%s</button></td>', $status_color[$elem], $status_code[$elem]);
                        } elseif($cnt == 6){
                            if($flag == 0 || $room_id == 10){
                                // 面试
                                for($i = 0; $i < 5; $i++){
                                    $button = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), $i, $elem, $button_color[$i], $button_text[$i]);
                                    if ($i == 0) $button = substr($button, 0, -5);
                                    elseif ($i == 1) $button = substr($button, 4);
                                    $html .= $button;
                                }
                            } else{
                                // 录取
                                // 5-8 录取 不录取
                                // 9 录取
                                // 11-14 删除
                                if($room_id >= 5 && $room_id <= 8){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 6, $elem, 'danger', '不录取');
                                } elseif($room_id == 9){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = '';
                                } else{
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 7, $elem, 'danger', '删除');
                                    $button2 = '';
                                }
                                $button_info = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 4, $elem, $button_color[4], $button_text[4]);
                                $html .= $button_info.$button1.$button2;
                            }
                        }
                    }
                $html .= '</tr>';
                }
                foreach($records2 as $record){
                    $html .= '<tr>';
                    $cnt = 0;
                    foreach ($record as $elem) {
                        $cnt++;
                        if ($cnt < 5){
                            $html .= sprintf('<td>%s</td>', $elem);
                        } elseif($cnt == 5){
                            if($elem > 4) $elem = 4;
                            $html .= sprintf('<td><button type="button" class="btn btn-%s">%s</button></td>', $status_color[$elem], $status_code[$elem]);
                        } elseif($cnt == 6){
                            if($flag == 0 || $room_id == 10){
                                // 面试
                                for($i = 0; $i < 5; $i++){
                                    $button = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), $i, $elem, $button_color[$i], $button_text[$i]);
                                    if ($i == 0) $button = substr($button, 0, -5);
                                    elseif ($i == 1) $button = substr($button, 4);
                                    $html .= $button;
                                }
                            } else{
                                // 录取
                                // 5-8 录取 不录取
                                // 9 录取
                                // 11-14 删除
                                if($room_id >= 5 && $room_id <= 8){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 6, $elem, 'danger', '不录取');
                                } elseif($room_id == 9){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = '';
                                } else{
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 7, $elem, 'danger', '删除');
                                    $button2 = '';
                                }
                                $button_info = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 4, $elem, $button_color[4], $button_text[4]);
                                $html .= $button_info.$button1.$button2;
                            }
                        }
                    }
                $html .= '</tr>';
                }
                foreach($records3 as $record){
                    $html .= '<tr>';
                    $cnt = 0;
                    foreach ($record as $elem) {
                        $cnt++;
                        if ($cnt < 5){
                            $html .= sprintf('<td>%s</td>', $elem);
                        } elseif($cnt == 5){
                            if($elem > 4) $elem = 4;
                            $html .= sprintf('<td><button type="button" class="btn btn-%s">%s</button></td>', $status_color[$elem], $status_code[$elem]);
                        } elseif($cnt == 6){
                            if($flag == 0 || $room_id == 10){
                                // 面试
                                for($i = 0; $i < 5; $i++){
                                    $button = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), $i, $elem, $button_color[$i], $button_text[$i]);
                                    if ($i == 0) $button = substr($button, 0, -5);
                                    elseif ($i == 1) $button = substr($button, 4);
                                    $html .= $button;
                                }
                            } else{
                                // 录取
                                // 5-8 录取 不录取
                                // 9 录取
                                // 11-14 删除
                                if($room_id >= 5 && $room_id <= 8){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 6, $elem, 'danger', '不录取');
                                } elseif($room_id == 9){
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 5, $elem, 'success', '录取');
                                    $button2 = '';
                                } else{
                                    $button1 = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 7, $elem, 'danger', '删除');
                                    $button2 = '';
                                }
                                $button_info = sprintf('<td><a href="%s/action/%s/%d"><button type="button" class="btn btn-%s">%s</button><a></td>', ENV('APP_URL'), 4, $elem, $button_color[4], $button_text[4]);
                                $html .= $button_info.$button1.$button2;
                            }
                        }
                    }
                $html .= '</tr>';
                }
            }
        } 
        if($flag == 0 || $room_id == 10){        
            $header = '
            <th>日期</th>
            <th>教室</th> 
            <th>时间</th> 
            <th>姓名</th>
            <th>状态</th>
            <th>候场教室操作</th>
            <th>面试教室操作</th>
            <th>候场教室操作</th>
            <th>信息</th>';
        } else{
            $header = '
            <th>日期</th>
            <th>教室</th> 
            <th>时间</th> 
            <th>姓名</th>
            <th>状态</th>
            <th>信息</th>
            <th>操作1</th>
            <th>操作2</th>';
        }
        $data = array($title, $header, $html);
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
        $rooms = array('2A-202', '2A-203');

        $admin = array('Defjia', 'Thd');
        $interviewer = array(['PanYu', '宋尚儒', '刘宇', 'Rinka'], []);
        $waiter = array('Waiter');
        $leaders = array('杨璐铭' => 0, 'PanYu' => 1, '邓卓辰' => 2, '齐紫妃' => 3, 'Defjia' => 0);
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
            return redirect(ENV('APP_URL').'/list/3/0')->with('message', $message);
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
        } elseif($aid == 5){
            // 录取
            if(in_array($name, $leaders)){
                $dpt_no = $leaders[$name];
                DB::table('record')->where('id', $uid)->update(['status' => $dpt_no + 8]);
                $message = '录取成功！';
            } else{
                $message = '无权限操作！';
            }
        } elseif($aid == 6){
            // 不录取
            if(in_array($name, $leaders)){
                $status = DB::table('record')->select('status')->where('id', $uid)->get()[0];
                $status++;
                DB::table('record')->where('id', $uid)->update(['status' => $status]);
                While($status >= 5 || $status <= 6){
                    // 可能为空
                    $tmp = ['second', 'third'];
                    $tmp2 = DB::table('record')->join('info', 'record.id', '=', 'info.id')->select('info.'.$tmp[$status-4])->where('record.id', $uid)->get()[0];
                    if($tmp2 == '') $status++;
                    else break;
                }
                $message = '退档成功！将进入下一志愿队列';
            } else{
                $message = '无权限操作！';
            }
        } elseif($aid == 7){
            // 删除
            if(in_array($name, $leaders) || in_array($name, $admin)){
                DB::table('record')->where('id', $uid)->update(['status' => 7]);
                $message = '删除成功！此人已进入捡漏队列！';
            } else{
                $message = '无权限操作！';
            }
        }
        return redirect(ENV('APP_URL').'/list/1/0')->with('message', $message);
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
    /*
    public function paris(){
        return redirect('/list/0/0')->with('message', '会场正在布置中！');
    }
    */

}
