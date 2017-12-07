server {

    listen 80 default;

    #listen 443 ssl default;
    #ssl_certificate cert/default.crt;
    #ssl_certificate_key cert/default.key;

    server_name_in_redirect off;

    include common/21.server_status;
    include common/22.server_error_page;

    set $pooldir {XS.WEB};

    # ������anrip.com��������ת��www.anrip.com
    if ($host ~ ^([^\.]+)\.([^\.]+)$) {
        rewrite ^/?(.*)$ http://www.$host/$1 last;
    }

    # ��*.anrip.com�ĸ�Ŀ¼��ΪWeb/com.anrip/*
    if ($host ~ ([^\.]+)\.([^\.]+)\.([^\.]+)$) {
        set $webroot $pooldir/$3.$2/$1;
    }
    # ����Ŀ¼������,������ΪWeb/default
    if (!-d $webroot) {
        set $webroot $pooldir/default;
    }

    #Ĭ�ϲ���
    root  $webroot;
    index index.html index.htm index.php;

    #ģ��·��
    #try_files $uri $uri/ /index.php?q=$uri&$args;

    #����PHP����
    include suffix/default.php7.inc;

}