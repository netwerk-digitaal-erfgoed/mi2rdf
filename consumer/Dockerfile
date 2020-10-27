FROM node:latest

RUN apt-get update \
    && apt-get -y --no-install-recommends install amqp-tools git mysql-client raptor2-utils python3-pip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* 

COPY onmessage.sh /
COPY startup.sh /
RUN chmod a+x /*.sh

RUN npm install -g jsonld-cli
#RUN npm install -g raptor
RUN git clone https://github.com/hetutrechtsarchief/MFXML-to-JSONLD.git && cd MFXML-to-JSONLD ; npm install

# moet anders opgelost worden...

RUN mkdir -p /filestore/131
RUN wget https://raw.githubusercontent.com/hetutrechtsarchief/Waterlandsarchief/master/skipfields.csv?token=AAAY3PGPLH5ZFA76E47BYBS7UF5T2 -o /filestore/131/skipfields.csv

CMD ["/startup.sh"]