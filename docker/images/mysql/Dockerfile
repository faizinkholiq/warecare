# mysql
FROM mysql:5.7-debian

# timezone
RUN echo "Asia/Jakarta" > /etc/timezone
RUN rm -f /etc/localtime
RUN dpkg-reconfigure -f noninteractive tzdata

# delete the fakiiin repo
RUN rm -rf /etc/apt/sources.list.d/mysql.list

# hide debian detail
ARG DEBIAN_FRONTEND=noninteractive