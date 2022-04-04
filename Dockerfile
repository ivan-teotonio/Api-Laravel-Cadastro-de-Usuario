FROM wyveo/nginx-php-fpm:latast

COPY . /usr/share/nginx/html
COPY nginx/.conf/etc/nginx/conf.d/default.conf

WORKDIR /usr/share/nginx/html 

RUN ls -s public html
RUN apt update; 

EXPOSE 80
    

