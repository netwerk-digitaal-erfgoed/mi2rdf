FROM node:latest

RUN apt-get update \
    && apt-get -y --no-install-recommends install amqp-tools git mysql-client raptor2-utils vim python3-pip \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

COPY onmessage.sh /
COPY startup.sh /
RUN chmod a+x /*.sh

RUN npm install -g jsonld-cli

RUN rm -Rf /MFXML-to-JSONLD \
    && git clone https://github.com/hetutrechtsarchief/MFXML-to-JSONLD.git clonetemp \
    && mv clonetemp MFXML-to-JSONLD \
    && cd MFXML-to-JSONLD ; npm install

# moet anders opgelost worden...
# hua / cg / demo / waterlandsarchief

RUN mkdir -p /filestore/39 \
    && mkdir -p /filestore/2 \
    && mkdir -p /filestore/0 \
    && mkdir -p /filestore/131 
#    && rm -f /filestore/131/* \
#    && wget https://raw.githubusercontent.com/hetutrechtsarchief/Waterlandsarchief/master/json/context.json?token=AAAY3PAOPPEXIJG3UKQWMIC7UKCVK -o /filestore/131/context.json \
#    && wget https://raw.githubusercontent.com/hetutrechtsarchief/Waterlandsarchief/master/skipfields.csv?token=AAAY3PAIT7UZSDKBVMYAL2S7UKDAQ -o /filestore/131/skipfields.csv \
#    && cp /filestore/131/context.json /filestore/39/ && cp /filestore/131/skipfields.csv /filestore/39/ \
#    && cp /filestore/131/context.json /filestore/2/ && cp /filestore/131/skipfields.csv /filestore/2/ \
#    && cp /filestore/131/context.json /filestore/0/ && cp /filestore/131/skipfields.csv /filestore/0/

CMD ["/startup.sh"]
