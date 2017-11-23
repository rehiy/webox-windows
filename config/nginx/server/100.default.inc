server {

    listen 80 default;

    #listen 443 ssl default;
    #ssl_certificate cert/default.crt;
    #ssl_certificate_key cert/default.key;

    server_name_in_redirect off;

    include common/21.server_status;
    include common/22.server_error_page;

    set $pooldir {XS.WEB};

    # 将形似anrip.com的请求跳转到www.anrip.com
    if ($host ~ ^([^\.]+)\.([^\.]+)$) {
        rewrite ^/?(.*)$ http://www.$host/$1 last;
    }

    # 将*.anrip.com的根目录设为Web/com.anrip/*
    if ($host ~ ([^\.]+)\.([^\.]+)\.([^\.]+)$) {
        set $webroot $pooldir/$3.$2/$1;
    }
    # 若根目录不存在,则设置为Web/default
    if (!-d $webroot) {
        set $webroot $pooldir/default;
    }

    #默认参数
    root  $webroot;
    index index.html index.htm index.php;

    #模拟路由
    #try_files $uri $uri/ /index.php?q=$uri&$args;

    #处理PHP请求
    include suffix/default.php7.inc;

}
