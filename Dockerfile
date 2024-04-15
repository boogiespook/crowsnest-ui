FROM registry.redhat.io/rhel9/php-81@sha256:caeb538289421482a41594fbd0ce3420570772b91fc9476fc88792f8f635fe5e 
MAINTAINER Chris Jenkins "chris@chrisj.co.uk"
EXPOSE 8080
COPY . /opt/app-root/src
CMD /bin/bash -c 'php -S 0.0.0.0:8080'
