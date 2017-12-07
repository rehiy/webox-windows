##
# Misc Config
##

scgi_temp_path {WB.TMP}/nginx/scgi;
uwsgi_temp_path {WB.TMP}/nginx/uwsgi;
fastcgi_temp_path {WB.TMP}/nginx/fastcgi;
client_body_temp_path {WB.TMP}/nginx/client_body;
proxy_temp_path {WB.TMP}/nginx/proxy;

client_header_buffer_size 32k;
client_body_buffer_size 128k;
client_max_body_size 30m;
large_client_header_buffers 4 32k;
server_names_hash_bucket_size 128;

#limit_conn_zone $binary_remote_addr zone=perip:10m;
#limit_conn_zone $server_name zone=perserver:10m;

##
# gzip
##

gzip on;
gzip_disable "msie6";

gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_buffers 16 8k;
gzip_http_version 1.1;
gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript;

##
# ssi
##

ssi on;
ssi_silent_errors on;
ssi_types text/shtml;
