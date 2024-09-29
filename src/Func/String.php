<?php
declare(strict_types=1);

namespace Naroat\PhpHelper\Func;

/**
 * 生成随机数.
 * @param number $length
 * @return number
 */
if (! function_exists('generate_number')) {
    function generate_number($length = 6)
    {
        return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }
}

/**
 * 生成随机字符串.
 * @param number $length
 * @param string $chars
 * @return string
 */
if (! function_exists('generate_string')) {
    function generate_string($length = 6, $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz')
    {
        $chars = str_split($chars);

        $chars = array_map(function ($i) use ($chars) {
            return $chars[$i];
        }, array_rand($chars, $length));

        return implode($chars);
    }
}


/**
 * 人民币转换大写
 */
if (!function_exists('rmb_upper')) {
    function rmb_upper($num)
    {
        $num = round($num,2);  //取两位小数
        $num = ''.$num;  //转换成数字
        $arr = explode('.',$num);

        $str_left = $arr[0];
        $str_right = $arr[1] ?? 0;

        $len_left = strlen($str_left); //小数点左边的长度
        $len_right = strlen($str_right); //小数点右边的长度

        //循环将字符串转换成数组，
        for($i=0;$i<$len_left;$i++)
        {
            $arr_left[] = substr($str_left,$i,1);
        }

        for($i=0;$i<$len_right;$i++)
        {
            $arr_right[] = substr($str_right,$i,1);
        }

        //构造数组$daxie
        $daxie = array(
            '0'=>'零',
            '1'=>'壹',
            '2'=>'贰',
            '3'=>'叁',
            '4'=>'肆',
            '5'=>'伍',
            '6'=>'陆',
            '7'=>'柒',
            '8'=>'捌',
            '9'=>'玖',
        );

        //循环将数组$arr_left中的值替换成大写
        foreach($arr_left as $k => $v)
        {
            $arr_left[$k] = $daxie[$v];
            switch($len_left--)
            {
                //数值后面追加金额单位
                case 5:
                    $arr_left[$k] .= '万';break;
                case 4:
                    $arr_left[$k] .= '千';break;
                case 3:
                    $arr_left[$k] .= '百';break;
                case 2:
                    $arr_left[$k] .= '十';break;
                default:
                    $arr_left[$k] .= '元';break;
            }
        }

        foreach($arr_right as $k =>$v)
        {
            $arr_right[$k] = $daxie[$v];
            switch($len_right--)
            {
                case 2:
                    $arr_right[$k] .= '角';break;
                default:
                    $arr_right[$k] .= '分';break;
            }
        }

        //将数组转换成字符串，并拼接在一起
        $new_left_str = implode('',$arr_left);
        $new_right_str = implode('',$arr_right);

        $new_str = $new_left_str.$new_right_str;

        //如果金额中带有0，大写的字符串中将会带有'零千零百零十',这样的字符串，需要替换掉
        $new_str = str_replace('零万','零',$new_str);
        $new_str = str_replace('零千','零',$new_str);
        $new_str = str_replace('零百','零',$new_str);
        $new_str = str_replace('零十','零',$new_str);
        $new_str = str_replace('零零零','零',$new_str);
        $new_str = str_replace('零零','零',$new_str);
        $new_str = str_replace('零元','元',$new_str);
        if ($new_str == "元零分") {
            $new_str = '零元零分';
        }
        return $new_str;
    }
}


if (!function_exists('encode_hashids')) {
    /**
     * 加密数字id到hashid
     * @param $name
     * @param $id
     * @return bool|string
     */
    function encode_hashids($name, $id)
    {
        $config = config('hash.' . $name);

        if (empty($config)) {
            return false;
        }

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        //实例化Hashids
        switch ($config['level']) {
            case 1:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 2:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            case 3:
                $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
        }

        $hashids = new \Hashids\Hashids($config['salt'], $config['length'], $alphabet);
        $str = $hashids->encode($id);
        unset($hashids);
        return $str;
    }
}

if (!function_exists('decode_hashids')) {
    /**
     * 解密数字id到hashid
     * @param $name
     * @param $hashid
     * @return bool
     */
    function decode_hashids($name, $hashid)
    {
        $config = config('hash.' . $name);

        if (empty($config)) {
            return false;
        }

        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

        //实例化Hashids
        switch ($config['level']) {
            case 1:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 2:
                $alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            case 3:
                $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
        }

        $hashids = new \Hashids\Hashids($config['salt'], $config['length'], $alphabet);
        $ids = $hashids->decode($hashid);
        unset($hashids);
        if (!isset($ids[0])) {
            return false;
        }
        return $ids[0];
    }
}

if (!function_exists('hide_email')) {
    /**
     * 私隐化邮箱
     * @param $email
     * @return string
     */
    function hide_email($email)
    {
        if (empty($email)) {
            return '';
        }

        $email_array = explode("@", $email);
        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($email, 0, 3); //邮箱前缀
        $count = 0;
        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $email, -1, $count);
        $rs = $prevfix . $str;
        return $rs;
    }
}

if (!function_exists('hide_phone')) {
    /**
     * 私隐化手机号码
     * @param $phone
     * @return string
     */
    function hide_phone($phone)
    {
        if (empty($phone)) {
            return '';
        }

        $str = substr_replace($phone, '****', 3, 4);

        return $str;
    }
}

if (!function_exists('cut_html')) {
    /**
     * 去掉富文本标签
     * @param $content
     * @return string
     */
    function cut_html($content, $length = 100)
    {
        $content_01 = $content;//从数据库获取富文本content
        $content_02 = htmlspecialchars_decode($content_01);//把一些预定义的 HTML 实体转换为字符
        $content_03 = str_replace("&nbsp;", "", $content_02);//将空格替换成空
        $contents = strip_tags($content_03);//函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
        $con = mb_substr($contents, 0, $length, "utf-8");//返回字符串中的前100字符串长度的字符
        return $con;
    }
}


if (! function_exists('hide_str')) {
    /**
     * +----------------------------------------------------------
     * 将一个字符串部分字符用*替代隐藏
     * +----------------------------------------------------------.
     * @param string $string 待转换的字符串
     * @param int $bengin 起始位置，从0开始计数，当$type=4时，表示左侧保留长度
     * @param int $len 需要转换成*的字符个数，当$type=4时，表示右侧保留长度
     * @param int $type 转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
     * @param string $glue 分割符
     *                     +----------------------------------------------------------
     * @return string 处理后的字符串
     *                +----------------------------------------------------------
     */
    function hide_str($string, $bengin = 0, $len = 4, $type = 0, $glue = '@')
    {
        if (empty($string)) {
            return false;
        }
        $array = [];
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, 'utf8');
                $string = mb_substr($string, 1, $strlen, 'utf8');
                $strlen = mb_strlen($string);
            }
        }
        if ($type == 0) {
            for ($i = $bengin; $i < ($bengin + $len); ++$i) {
                if (isset($array[$i])) {
                    $array[$i] = '*';
                }
            }
            $string = implode('', $array);
        } elseif ($type == 1) {
            $array = array_reverse($array);
            for ($i = $bengin; $i < ($bengin + $len); ++$i) {
                if (isset($array[$i])) {
                    $array[$i] = '*';
                }
            }
            $string = implode('', array_reverse($array));
        } elseif ($type == 2) {
            $array = explode($glue, $string);
            $array[0] = hideStr($array[0], $bengin, $len, 1);
            $string = implode($glue, $array);
        } elseif ($type == 3) {
            $array = explode($glue, $string);
            $array[1] = hideStr($array[1], $bengin, $len, 0);
            $string = implode($glue, $array);
        } elseif ($type == 4) {
            $left = $bengin;
            $right = $len;
            $tem = [];
            for ($i = 0; $i < ($length - $right); ++$i) {
                if (isset($array[$i])) {
                    $tem[] = $i >= $left ? '*' : $array[$i];
                }
            }
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; ++$i) {
                $tem[] = $array[$i];
            }
            $string = implode('', $tem);
        }
        return $string;
    }
}

if (! function_exists('cut_str')) {
    /**
     * 按符号截取字符串的指定部分.
     * @param string $str 需要截取的字符串
     * @param string $sign 需要截取的符号
     * @param int $number 如是正数以0为起点从左向右截 负数则从右向左截
     * @return string 返回截取的内容
     */
    function cut_str($str, $sign, $number)
    {
        $array = explode($sign, $str);
        $length = count($array);
        if ($number < 0) {
            $new_array = array_reverse($array);
            $abs_number = abs($number);
            if ($abs_number > $length) {
                return 'error';
            }
            return $new_array[$abs_number - 1];
        }
        if ($number >= $length) {
            return 'error';
        }
        return $array[$number];
    }
}

if(! function_exists('rand_string')){
    /**
     * 产生随机字串，可用来自动生成密码
     * 默认长度6位 字母和数字混合 支持中文
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 其它 混合
     * @param string $addChars 额外字符
     * @return string
     */
    function rand_string($len = 6, $type = '', $addChars = '')
    {
        $str = '';
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 4:
                $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
                break;
            case 5:
                $chars='ABCDEFGHJKLMNPQRSTUVWXY'.$addChars;
                break;
            default:
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($len > 10) {
//位数过长重复字符串一定次数
            $chars = 1 == $type ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if (4 != $type) {
            $chars = str_shuffle($chars);
            $str   = substr($chars, 0, $len);
        } else {
            // 中文随机字
            for ($i = 0; $i < $len; $i++) {
                $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1, 'utf-8', false);
            }
        }
        return $str;
    }
}