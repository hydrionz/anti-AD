<?php
/**
 * easylist extend
 *
 * @file easylist-optimizer.php
 * @date 2021-05-01
 * @author gently
 *
 */
set_time_limit(0);

error_reporting(7);

define('START_TIME', microtime(true));
define('ROOT_DIR', dirname(__DIR__) . '/');
define('LIB_DIR', ROOT_DIR . 'lib/');

$black_domain_list = require_once LIB_DIR . 'black_domain_list.php';
require_once LIB_DIR . 'addressMaker.class.php';
define('WILDCARD_SRC', ROOT_DIR . 'origin-files/wildcard-src-easylist.txt');
define('WHITERULE_SRC', ROOT_DIR . 'origin-files/whiterule-src-easylist.txt');

$ARR_MERGED_WILD_LIST = array(
    'ad*.udn.com$dnstype=A|CNAME' => null,
    '*.mgr.consensu.org' => null,
    'vs*.gzcu.u3.ucweb.com' => null,
    'ad*.goforandroid.com' => null,
    'bs*.9669.cn' => null,
    '*serror*.wo.com.cn' => null,
    '*mistat*.xiaomi.com' => null,
    'affrh20*.com' => null,
    'assoc-amazon.*' => null,
    'clkservice*.youdao.com' => null,
    'dsp*.youdao.com' => null,
    'pussl*.com' => null,
    'putrr*.com' => null,
    't*.a.market.xiaomi.com' => null,
    'ad*.bigmir.net' => null,
    'log*.molitv.cn' => null,
    'adm*.autoimg.cn' => null,
    'cloudservice*.kingsoft-office-service.com' => null,
    'gg*.51cto.com' => null,
    'log.*.hunantv.com' => null,
    'iflyad.*.openstorage.cn' => null,
    '*customstat*.51togic.com' => null,
//    'appcloud*.zhihu.com' => null, // #344
    'ad*.molitv.cn' => null,
    'ads*-adnow.com' => null,
    'aeros*.tk' => null,
    'analyzer*.fc2.com' => null,
    'admicro*.vcmedia.vn' => null,
    'xn--xhq9mt12cf5v.*' => null,
    'freecontent.*' => null,
    'hostingcloud.*' => null,
    'jshosting.*' => null,
    'flightzy.*' => null,
    'sunnimiq*.cf' => null,
    'admob.*' => null,
    '*log.droid4x.cn' => null,
    '*tsdk.vivo.com.cn' => null,
    '*.mmstat.com' => null,
//    'sf*-ttcdn-tos.pstatp.com' => null,
    'f-log*.grammarly.io' => null,
    '24log.*' => null,
    '24smi.*' => null,
    'ad-*.wikawika.xyz' => null,
    'ablen*.tk' => null,
    'darking*.tk' => null,
    'doubleclick*.xyz' => null,
    'thepiratebay.*' => null,
    'adserver.*' => null,
    'clientlog*.music.163.com' => null,
    'brucelead*.com' => null,
    'gostats.*' => null,
    'gralfusnzpo*.top' => null,
    'oiwjcsh*.top' => null,
    '*-analytics*.huami.com' => null,
    'count*.pconline.com.cn' => null,
    'qchannel*.cn' => null,
    'sda*.xyz' => null,
    'ad-*.com' => null,
    'ad-*.net' => null,
    'webads.*' => null,
    'web-stat.*' => null,
    'waframedia*.*' => null,
    'wafmedia*.*' => null,
    'voluumtrk*.com' => null,
    'vmm-satellite*.com' => null,
    'vente-unique.*' => null,
    'vegaoo*.*' => null,
    'umtrack*.com' => null,
    'grjs0*.com' => null,
    'imglnk*.com' => null,
    'admarvel*.*' => null,
    'admaster*.*' => null,
    'adsage*.*' => null,
    'adsensor*.*' => null,
    'adservice*.*' => null,
    'adsh*.*' => null,
    'adsmogo*.*' => null,
    'adsrvmedia*.*' => null,
    'adsserving*.*' => null,
    'adsystem*.*' => null,
    'adwords*.*' => null,
    'analysis*.*' => null,
    'applovin*.*' => null,
    'appsflyer*.*' => null,
    'domob*.*' => null,
    'duomeng*.*' => null,
    'dwtrack*.*' => null,
    'guanggao*.*' => null,
    'lianmeng*.*' => null,
    //'monitor*.*' => null,
    'omgmta*.*' => null,
    'omniture*.*' => null,
    'openx*.*' => null,
    'partnerad*.*' => null,
    'pingfore*.*' => null,
    'socdm*.*' => null,
    'supersonicads*.*' => null,
    'tracking*.*' => null,
    'usage*.*' => null,
    'wlmonitor*.*' => null,
    'zjtoolbar*.*' => null,
    'engage.3m*' => null,
    '*.actonservice.com' => null,
    '*-cor0*.api.p001.1drv.com' => null,
    '*33*-*.1drv.com' => null,
    '2cnjuh34j*.com' => null,
    'ssc.southpark*' => null,
    'tr.*.espmp-*fr.net' => null,
    'tdep.vacansoleil.*' => null,
    'da.hornbach.*' => null,
    '*us*watcab*.blob.core.windows.net' => null,
    'xn--wxtr9fwyxk9c.*' => null,
);

$ARR_REGEX_LIST = array(
    '/9377[a-z]{2}\.com$/' => null,
    '/^(\S+\.)?ad(s?[\d]+|m|s)?\./' => null,
//    '/^(\S+\.)?advert/$dnstype=~CNAME' => null, // TODO dnstype工作不正常，暂时关闭此规则
    '/^(\S+\.)?affiliat(es?[0-9a-z]*?|ion[0-9\-a-z]*?|ly[0-9a-z\-]*?)\./' => null, // fixed #406
    '/^(\S+\.)?s?metrics\./' => null, // TODO 覆盖面很大
    '/afgr[\d]{1,2}\.com$/' => null,
    '/^(\S+\.)?analytics(\-|\.)/' => null,
    '/^(\S+\.)?counter(\-|\.)/' => null,
    '/^(\S+\.)?pixels?\./' => null,
    '/syma[a-z]\.cn$/' => null,
    '/^(\S+\.)?widgets?\./' => null,
    '/^(\S+\.)?(webstats?|swebstats?|mywebstats?)\./' => null,
    // '/^(\S+\.)?stat\..+?\.(com|cn|ru|it|de|cz|net|kr|ai|pl|th|fi|fr|jp|hu|bz|sk|se)$/' => null,
    '/^(\S+\.)?track(ing)?\./' => null,
    '/^(\S+\.)?tongji\./' => null,
    '/^(\S+\.)?toolbar\./' => null,
    '/^(\S+\.)?adservice\.google\./' => null,
    '/^(\S+\.)?d[\d]+\.sina(img)?(\.com)?\.cn/' => null,
    '/^(\S+\.)?sax[\dns]?\.sina\.com\.cn/' => null,
    '/delivery([\d]{2}|dom|modo).com$/' => null,
    '/^(\S+\.)?[c-s]ads(abs|abz|ans|anz|ats|atz|del|ecs|ecz|ims|imz|ips|ipz|kis|kiz|oks|okz|one|pms|pmz)\.com/' => null,
    '/^(\S+\.)?([a-z\d\-]+\.)?(?!xn--)[^\.\/]{26,}\.(com|net|cn)(\.cn)?$/' => null, //超长域名
    '/^(\S+\.)?11599[\da-z]{2,20}\.com$/' => null, //"澳门新葡京"系列
    '/^(\S+\.)?61677[\da-z]{0,20}\.com$/' => null, //"澳门新葡京"系列
    '/^(\S+\.)?[0-9a-f]{15,}\.com$/' => null, //15个字符以上的16进制域名
    '/^(\S+\.)?[0-9a-z]{16,}\.xyz$/' => null, //16个字符以上的.xyz域名
    '/^(\S+\.)?6699[0-9]\.top$/' => null, //连号
    '/^(\S+\.)?abie[0-9]+\.top$/' => null, //连号
    '/^(\S+\.)?ad[0-9]{3,}m.com$/' => null, //连号
    '/^(\S+\.)?aj[0-9]{4,}.online$/' => null, //连号
    '/^(\S+\.)?xpj[0-9]\.net$/' => null, //连号
    '/^(\S+\.)?ylx-[0-9].com$/' => null, //连号
    '/^(\S+\.)?ali2[a-z]\.xyz$/' => null, //连号
    '/^(\S+\.)?777\-?partners?\.(net|com)$/' => null, //组合
    '/^(\S+\.)?voyage-prive\.[a-z]+(\.uk)?$/' => null, //组合
    '/^(\S+\.)?e7[0-9]{2,4}\.(net|com)?$/' => null, //组合
    '/^(\S+\.)?g[1-4][0-9]{8,9}\.com?$/' => null, //批量组合
    '/^(\S+\.)?hg[0-9]{4,5}\.com?$/' => null, //批量组合
    '/^(\S+\.)?333[1-9]{2}[0-9]{2}\.com?$/' => null, //批量组合
    '/^(\S+\.)?5551[0-9]{3}\.com?$/' => null, //批量组合

    // '/^(\S+\.)?(?=.*[a-f].*\.com$)(?=.*\d.*\.com$)[a-f0-9]{15,}\.com$/' => null,
);

//对通配符匹配或正则匹配增加的额外赦免规则
$ARR_WHITE_RULE_LIST = array(
    '@@||tongji.*kuwo.cn^' => 0,
    '@@||tracking.epicgames.com^' => 0,
    '@@||tracker.eu.org^' => 1, //强制加白，BT tracker，有形如2.tracker.eu.org的域
    '@@||stats.uptimerobot.com^' => 0, //uptimerobot监测相关
    '@@||track.sendcloud.org^' => 0, //邮件退订域名
    '@@||log.mmstat.com^' => 0, //修复优酷视频显示禁用了cookie
    '@@||adm.10jqka.com.cn^' => 0, //同花顺
    '@@||center-h5api.m.taobao.com^' => 1, //h5页面
    '@@||app.adjust.com^' => 1, //https://github.com/AdguardTeam/AdGuardSDNSFilter/pull/186
    '@@||widget.weibo.com^' => 0, //微博外链
    '@@||uland.taobao.com^' => 1, //淘宝coupon #83
    '@@||advertisement.taobao.com^' => 1, //CNAME 被杀，导致s.click.taobao.com等服务异常
    '@@||baozhang.baidu.com^' => 1, //CNAME e.shifen.com 
    '@@||tongji.edu.cn^' => 1, // 同济大学
    '@@||tongji.cn^' => 1, // 同济大学 #281
    '@@||ad.siemens.com.cn^' => 1, // 西门子下载中心
    '@@||sdkapi.sms.mob.com^' => 1, // 短信验证码 #127
    '@@||stats.gov.cn^' => 1, // 国家统计局 #144
    '@@||tj.gov.cn^' => 1,
    '@@||sax.sina.com.cn^' => 1, // #155
    '@@||api.ad-gone.com^' => 1, // #207
    '@@||news-app.abumedia.yql.yahoo.com^' => 1, // #206
    '@@||meizu.coapi.moji.com^' => 1, // #217
    '@@||track.cpau.info^' => 1, // #251
    '@@||passport.bobo.com^' => 1, // #265
    '@@||stat.jseea.cn^' => 1, // #279
    '@@||widget.intercom.io^' => 1, // #280
    '@@||track.toggl.com^' => 1, // #307
    '@@||www.msftconnecttest.com^' => 1, // #327
    '@@||storage.live.com^' => 1, // #333
    '@@||skyapi.onedrive.live.com^' => 1, // #333
    '@@||counter-strike.net^' => 1, // #332
    '@@||ftp.bmp.ovh^' => 1, // #353
    '@@||profile*.se.360.cn^' => 1, // #381
    '@@||pic.iask.cn^' => 1, // #397
    '@@||ad.jp^' => 1, // #399
    '@@||ad.azure.com^' => 1, // #399
    '@@||ad.cityu.edu.hk^' => 1, // #398
    '@@||edge-enterprise.activity.windows.com^' => 1, // #401
    '@@||edge.activity.windows.com^' => 1, // #401
    '@@||tracking-protection.cdn.mozilla.net^' => 1, // #407
    '@@||skydrivesync.policies.live.net^' => 1, // #409

);

//针对上游赦免规则anti-AD不予赦免的规则，即赦免名单的黑名单
$ARR_WHITE_RULE_BLK_LIST = array(
    '@@||ads.nipr.ac.jp^' => null,
);

//针对上游通配符规则中anti-AD不予采信的规则，即通配符黑名单
$ARR_WILD_BLK_LIST = array(
    'cnt*rambler.ru' => null,
    'um*.com' => null,
);

if(PHP_SAPI != 'cli'){
    die('nothing.');
}

$src_file = '';
try{
    $file = $argv[1];
    $src_file = ROOT_DIR . $file;
}catch(Exception $e){
    echo "get args failed.", $e->getMessage(), "\n";
    die(0);
}

if(empty($src_file) || !is_file($src_file)){
    echo 'src_file:', $src_file, ' is not found.';
    die(0);
}

if(!is_file(WILDCARD_SRC) || !is_file(WHITERULE_SRC)){
    echo 'key file is not found.';
    die(0);
}

//$src_fp = fopen($src_file, 'r');
$wild_fp = fopen(WILDCARD_SRC, 'r');
//$new_fp = fopen($src_file . '.txt', 'w');

$wrote_wild = array();
$arr_wild_src = array();

while(!feof($wild_fp)){
    $wild_row = fgets($wild_fp, 512);
    if(empty($wild_row)){
        continue;
    }
    if(!preg_match('/^\|\|?([\w\-\.\*]+?)\^(\$([^=]+?,)?(image|third-party|script)(,[^=]+)?)?$/', $wild_row, $matches)){
        continue;
    }

    if(array_key_exists($matches[1], $ARR_WILD_BLK_LIST)){
        continue;
    }

    $matched = false;
    foreach($ARR_REGEX_LIST as $regex_str => $regex_row){
        $arr_regex = explode('/$', $regex_str);
        $final_regex = $regex_str;
        if(count($arr_regex) > 1){
            $final_regex = $arr_regex[0] . '/';
        }
        if(preg_match($final_regex, str_replace('*', '',$matches[1]))){
            $matched = true;
        }
    }
    if($matched){
        continue;
    }
    $arr_wild_src[$matches[1]] = [];
}
fclose($wild_fp);

$arr_wild_src = array_merge($arr_wild_src, $ARR_MERGED_WILD_LIST);

$insert_pos = $written_size = $line_count = 0;

$src_content = file_get_contents($src_file);
$src_tail_content = '';
$tmp_replaced_content = '';

// 清洗正在表达式匹配
foreach($ARR_REGEX_LIST as $regex_str => $regex_row){
    $arr_regex = explode('/$', $regex_str);
    $final_regex = $regex_str;
    if(count($arr_regex) > 1){
        $final_regex = $arr_regex[0] . '/';
    }
    $php_regex = str_replace(array('/^', '$/'), array('/^\|\|', '\^'), $final_regex);
    $php_regex = preg_replace('/(.+?[^$])\/$/', '\1.*\^', $php_regex);
    $php_regex .= "\n/m";

//    echo $php_regex, "\n";
    $tmp_replaced_content = preg_replace($php_regex, '', $src_content);
//    $tmp_replaced_content = str_replace('__DEL'.PHP_EOL, '', $tmp_replaced_content);
    if($tmp_replaced_content == $src_content){
        continue;
    }
    $src_content = $tmp_replaced_content;
    $tmp_replaced_content = '';
    $src_tail_content .= $final_regex . "\n";
    $line_count++;
}

// 清洗*号模糊匹配
foreach($arr_wild_src as $wild_rule => $wild_value){
    $arr_wild_rule = explode('$', $wild_rule);
    $final_regex = $wild_rule;
    if(count($arr_wild_rule) > 1){
        $final_regex = $arr_wild_rule[0];
    }

    $php_regex = '/^\|\|(\S+\.)?' . str_replace(array('.', '*', '-'), array('\\.', '.*', '\\-'), $final_regex) . "\^\n/m";
//    echo $php_regex, "\n";
    $tmp_replaced_content = preg_replace($php_regex, '', $src_content);
//    $tmp_replaced_content = str_replace('__DEL'.PHP_EOL, '', $tmp_replaced_content);
    if($tmp_replaced_content == $src_content){
        continue;
    }
    $src_content = $tmp_replaced_content;
    $tmp_replaced_content = '';
    $src_tail_content .= '||' . $final_regex . "^\n";
    $line_count++;
}

//按需写入白名单规则
$whiterule = file(WHITERULE_SRC, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$whiterule = array_fill_keys($whiterule, 0);
$ARR_WHITE_RULE_LIST = array_merge($whiterule, $ARR_WHITE_RULE_LIST);
foreach($ARR_WHITE_RULE_LIST as $row => $v){
    if(empty($row) || substr($row, 0, 1) !== '@' || substr($row, 1, 1) !== '@'){
        continue;
    }
    $matches = array();
    if(!preg_match('/^@@\|\|([0-9a-z\.\-\*]+?)\^/', $row, $matches)){
        continue;
    }

    if(array_key_exists("@@||${matches[1]}^", $ARR_WHITE_RULE_BLK_LIST)){
        continue;
    }
    if($v === 1){
        $wrote_whitelist[$matches[1]] = null;
        $src_tail_content .= "@@||${matches[1]}^\n";
        $line_count++;
        continue;
    }

    echo $matches[1], "\n";



//    foreach($wrote_wild as $core_str => $val){
//        if(substr($core_str, 0, 1) === '/'){
//            $match_rule = $core_str;
//            $arr_regex = explode('/$', $match_rule);
//        }else{
//            $match_rule = str_replace(array('.', '*'), array('\\.', '.*'), $core_str);
//            $match_rule = "/^${match_rule}/";
//        }
//
//
//        $final_regex = $match_rule;
//        if(count($arr_regex) > 1){
//            $final_regex = $arr_regex[0] . '/';
//        }
//
//
//        if(preg_match($final_regex, $matches[1])){
//            $domain = addressMaker::extract_main_domain($matches[1]);
//            if(array_key_exists($domain, $black_domain_list) ||
//                (is_array($black_domain_list[$domain]) && in_array($matches[1], $black_domain_list[$domain]))
//            ){
//                continue;
//            }
//            if(array_key_exists($matches[1], $wrote_whitelist)){
//                continue;
//            }
//            $wrote_whitelist[$matches[1]] = null;
//            fwrite($new_fp, "@@||${matches[1]}^\n");
//            $line_count++;
//        }
//    }
}



//echo "LINE COUNT:", $line_count, "\t", substr_count($src_content, "\n");
//echo "\n\n\n\n\n";
//echo $src_tail_content;
//echo $src_content;





//while(!feof($src_fp)){
//    $row = fgets($src_fp, 512);
//    if(empty($row)){
//        continue;
//    }
//
//    if((substr($row, 0, 1) === '!')){
//        if(substr($row, 0, 13) === '!Total lines:'){
//            $insert_pos = $written_size;
//        }
//        $written_size += fwrite($new_fp, $row);
//        continue;
//    }
//
////    if(!preg_match('/^\|.+?/', $row)){
////        $written_size += fwrite($new_fp, $row);
////        continue;
////    }
//
//    $matched = false;
//    foreach($ARR_REGEX_LIST as $regex_str => $regex_row){
//        $arr_regex = explode('/$', $regex_str);
//        $final_regex = $regex_str;
//        if(count($arr_regex) > 1){
//            $final_regex = $arr_regex[0] . '/';
//        }
//        if(preg_match($final_regex, substr(trim($row), 2, -1))){
//            $matched = true;
//            if(!array_key_exists($regex_str, $wrote_wild)){
//                $written_size += fwrite($new_fp, "${regex_str}\n");
//                $line_count++;
//                $wrote_wild[$regex_str] = 1;
//            }
//            break;
//        }
//    }
//
//    if($matched){
//        continue;
//    }
//
//    foreach($arr_wild_src as $core_str => $wild_row){
//        $arr_wild_sub = explode('$', $core_str);
//        $match_rule = '';
//        if(count($arr_wild_sub) > 1){
//            $match_rule = str_replace(array('.', '*', '-'), array('\\.', '.*', '\\-'), $arr_wild_sub[0]);
//        }else{
//            $match_rule = str_replace(array('.', '*', '-'), array('\\.', '.*', '\\-'), $core_str);
//        }
//
//        if(preg_match("/\|(\S+\.)?${match_rule}/", $row)){
//            if(!array_key_exists($core_str, $wrote_wild)){
//                if(count($arr_wild_sub) > 1){
//                    $written_size += fwrite($new_fp, "||${arr_wild_sub[0]}^\$${arr_wild_sub[1]}\n");
//                }else{
//                    $written_size += fwrite($new_fp, "||${core_str}^\n");
//                }
//
//                $line_count++;
//                $wrote_wild[$core_str] = 1;
//            }
//            $matched = true;
//            break;
//        }
//    }
//
//    if($matched){
//        continue;
//    }
//    $written_size += fwrite($new_fp, $row);
//    $line_count++;
//}

//按需写入白名单规则
//$wrote_whitelist = array();
//$whiterule = file(WHITERULE_SRC, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
//$whiterule = array_fill_keys($whiterule, 0);
//$ARR_WHITE_RULE_LIST = array_merge($whiterule, $ARR_WHITE_RULE_LIST);
//foreach($ARR_WHITE_RULE_LIST as $row => $v){
//    if(empty($row) || substr($row, 0, 1) !== '@' || substr($row, 1, 1) !== '@'){
//        continue;
//    }
//    $matches = array();
//    if(!preg_match('/@@\|\|([0-9a-z\.\-\*]+?)\^/', $row, $matches)){
//        continue;
//    }
//
//    if(array_key_exists("@@||${matches[1]}^", $ARR_WHITE_RULE_BLK_LIST)){
//        continue;
//    }
//    if($v === 1){
//        $wrote_whitelist[$matches[1]] = null;
//        fwrite($new_fp, "@@||${matches[1]}^\n");
//        $line_count++;
//        continue;
//    }
//
//    foreach($wrote_wild as $core_str => $val){
//        if(substr($core_str, 0, 1) === '/'){
//            $match_rule = $core_str;
//            $arr_regex = explode('/$', $match_rule);
//        }else{
//            $match_rule = str_replace(array('.', '*'), array('\\.', '.*'), $core_str);
//            $match_rule = "/^${match_rule}/";
//        }
//
//
//        $final_regex = $match_rule;
//        if(count($arr_regex) > 1){
//            $final_regex = $arr_regex[0] . '/';
//        }
//
//
//        if(preg_match($final_regex, $matches[1])){
//            $domain = addressMaker::extract_main_domain($matches[1]);
//            if(array_key_exists($domain, $black_domain_list) ||
//                (is_array($black_domain_list[$domain]) && in_array($matches[1], $black_domain_list[$domain]))
//            ){
//                continue;
//            }
//            if(array_key_exists($matches[1], $wrote_whitelist)){
//                continue;
//            }
//            $wrote_whitelist[$matches[1]] = null;
//            fwrite($new_fp, "@@||${matches[1]}^\n");
//            $line_count++;
//        }
//    }
//}
//
//if(($insert_pos > 0) && (fseek($new_fp, $insert_pos) === 0)){
//    fwrite($new_fp, "!Total lines: {$line_count}\n");
//}
//
//fclose($src_fp);
//fclose($new_fp);
//rename($src_file . '.txt', $src_file);
//file_put_contents($src_file . '.md5', md5_file($src_file));
echo 'Time cost:', microtime(true) - START_TIME, "s, at ", date('m-d H:i:s'), "\n";
