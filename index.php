<?php


if(!empty($_SERVER['QUERY_STRING']) && is_numeric($_SERVER['QUERY_STRING'])) {

} else {

    $url = '';
    $domain = $_ENV = array();
    $jump = false;
    @include_once './data/sysdata/cache_domain.php';
    $_ENV['domain'] = $domain;
    if(empty($_ENV['domain'])) {

    } else {
        $_ENV['defaultapp'] = array('portal.php' => 'portal', 'forum.php' => 'forum', 'group.php' => 'group', 'home.php' => 'home');
        $_ENV['hostarr'] = explode('.', $_SERVER['HTTP_HOST']);
        $_ENV['domainroot'] = substr($_SERVER['HTTP_HOST'], strpos($_SERVER['HTTP_HOST'], '.')+1);
        if(!empty($_ENV['domain']['app']) && is_array($_ENV['domain']['app']) && in_array($_SERVER['HTTP_HOST'], $_ENV['domain']['app'])) {

        } elseif(!empty($_ENV['domain']['root']) && is_array($_ENV['domain']['root']) && in_array($_ENV['domainroot'], $_ENV['domain']['root'])) {

        } else {
            $jump = true;
        }
        if(empty($url) && empty($_ENV['curapp'])) {
            if(!empty($_ENV['domain']['defaultindex']) && !$jump) {

            } else {
                if($jump) {
                    $url = empty($_ENV['domain']['app']['default']) ? (!empty($_ENV['domain']['defaultindex']) ? $_ENV['domain']['defaultindex'] : 'forum.php') : 'http://'.$_ENV['domain']['app']['default'];
                } else {

                }
            }
        }
    }
}
if(!empty($url)) {
    $delimiter = strrpos($url, '?') ? '&' : '?';

    header("HTTP/1.1 301 Moved Permanently");
    header("location: $url");
} else {

}

function checkholddomain($domain) {
    global $_G;

    $domain = strtolower($domain);
    if(preg_match("/^[^a-z]/i", $domain)) return true;
    $holdmainarr = empty($_G['setting']['holddomain']) ? array('www') : explode('|', $_G['setting']['holddomain']);
    $ishold = false;
    foreach ($holdmainarr as $value) {
        if(strpos($value, '*') === false) {
            if(strtolower($value) == $domain) {
                $ishold = true;
                break;
            }
        } else {
            $value = str_replace('*', '.*?', $value);
            if(@preg_match("/$value/i", $domain)) {
                $ishold = true;
                break;
            }
        }
    }
    return $ishold;
}
?>