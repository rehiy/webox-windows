##
# Proxy Config
##

proxy_buffering on;
proxy_buffers 8 64k;
proxy_buffer_size 64k;
proxy_busy_buffers_size 128k;

proxy_connect_timeout 30;
proxy_read_timeout 180;
proxy_send_timeout 180;

proxy_cache_path {WB.TMP}/nginx/cache levels=1:2 keys_zone=proxy_cache:128m max_size=512m inactive=1d;

##
# header
##

proxy_set_header Host $host;
proxy_set_header X-Scheme $scheme;
proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
