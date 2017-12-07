##
# PHP Fast-CGI 71
##

location ~ \.php$ {

    include fastcgi_params;

    #fastcgi_param PHP_ADMIN_VALUE "open_basedir=$document_root/;{WB.TMP}/php71/";

    fastcgi_index index.php;
    fastcgi_pass  127.0.0.1:9701;

    fastcgi_buffer_size 128k;
    fastcgi_buffers 4 256k;
    fastcgi_busy_buffers_size 256k;
    fastcgi_temp_file_write_size 256k;
    fastcgi_intercept_errors on;

    fastcgi_connect_timeout 30;
    fastcgi_send_timeout 180;
    fastcgi_read_timeout 180;

}
