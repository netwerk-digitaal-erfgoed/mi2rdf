FROM node:latest

RUN apt-get update \
    && apt-get -y --no-install-recommends install amqp-tools git mysql-client \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* 

COPY onmessage.sh /
COPY startup.sh /
RUN chmod a+x /*.sh

RUN git clone https://github.com/netwerk-digitaal-erfgoed/MDWS-JSON-to-Turtle.git && cd MDWS-JSON-to-Turtle ; npm install
RUN git clone https://github.com/netwerk-digitaal-erfgoed/MDWS-to-JSON.git && cd MDWS-to-JSON ; npm install
RUN stat -c %y /MDWS-JSON-to-Turtle/index.js | sed -e "s/\\..*//" > /filestore/MDWS-JSON-to-Turtle.dat
RUN stat -c %y /MDWS-to-JSON/index.js | sed -e "s/\\..*//" > /filestore/MDWS-to-JSON.dat 

CMD ["/startup.sh"]