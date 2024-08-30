FROM ubuntu:22.04


RUN apt-get update && apt-get -y install golang-go wget supervisor

COPY main.go /tmp/main.go
RUN go build /tmp/main.go

COPY ./config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord"]