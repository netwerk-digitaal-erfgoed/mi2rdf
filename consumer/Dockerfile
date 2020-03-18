FROM node:latest

RUN apt-get update \
    && apt-get -y --no-install-recommends install amqp-tools git mysql-client \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* 

COPY onmessage.sh /
COPY startup.sh /
RUN chmod a+x /*.sh

RUN git clone https://github.com/netwerk-digitaal-erfgoed/MDWS-JSON-to-Turtle.git && cd MDWS-JSON-to-Turtle ; npm install
RUN git clone https://github.com/netwerk-digitaal-erfgoed/MDWS-to-JSON.git && cd MDWS-to-JSON ; npm install

CMD ["/startup.sh"]