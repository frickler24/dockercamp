FROM nginx

LABEL maintainer="frickler24@frickler24.de"

# Als erstes mal ein paar Tools und das notwendige PHP-Geraffel laden und installieren
# Die erste Zeile ist ein Linux-Patchday:
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y bash curl wget vim inetutils-*
RUN apt-get install -y php php-fpm php-gd

# Verzeichnisse anlegen, falls noch nicht vorhanden
# Unter /run/php merkt sich der php-fpm nachherseine Prozess-ID
RUN mkdir -p /run/php/

# Das Config-File für nginx, damit er weiß, was wir von ihm wollen
COPY _etc_nginx_conf.d_default.conf /etc/nginx/conf.d/default.conf

# Hier stehen ein paar Parameter für phpfpm,
# z.B. dass er über Port 9000 auf localhost erreichbar sein soll.
COPY _etc_php_7.0_fpm_php-fpm.conf /etc/php/7.0/fpm/php-fpm.conf
COPY www.conf /etc/php/7.0/fpm/pool.d/www.conf

# Eine HTML-Test-Datei (das ist die Standard nginx-Datei)
COPY _var_www_html_test.html /usr/share/nginx/html/test.html

# Hier lesen wir php5-GD Parameter (Graphics develop)
# und PHP-Standard-Params, wenn wir nginx und phpfpm richtig konfiguriert haben
COPY _var_www_html_test.php /usr/share/nginx/html/test.php

# Das Berechnen einer einzelnen Mandelbrot-Kachel
COPY mandel.php /usr/share/nginx/html/mandel.php

# Das gesamte Brötchen berechnen (über mandel.php)
COPY brot.php /usr/share/nginx/html/brot.php

# Starte phpfp und dann nginx so, dass er im Vordergrund bleibt
CMD /bin/sh -c "php-fpm7.3 && echo 'Starting nginx' && nginx -g 'daemon off;' && echo 'nginx stopped.'"

