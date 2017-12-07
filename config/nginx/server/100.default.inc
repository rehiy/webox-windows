server {

    listen 80 default_server;

    #listen 443 ssl default_server;
    #ssl_certificate cert/default.crt;
    #ssl_certificate_key cert/default.key;

    include common/21.server_status;
    include common/22.server_error_page;

    set $pooldir {WB.WEB};

    if ($host ~ ^([^\.]+)\.([^\.]+)$) {
        rewrite ^/?(.*)$ http://www.$host/$1 last;
    }
    if ($host ~ ([^\.]+)\.([^\.]+)\.([^\.]+)$) {
        set $sitedir $pooldir/$3.$2/$1;
    }

    if (!-d $sitedir) {
        set $sitedir $pooldir/default;
    }

    root  $sitedir;
    index index.html index.htm index.php;
    try_files $uri $uri/ /index.php?q=$uri&$args;

    include suffix/default.php7.inc;

}
