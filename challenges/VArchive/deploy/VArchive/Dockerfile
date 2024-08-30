FROM ubuntu:24.10

RUN apt update && apt install -y python3 python-is-python3 gcc

COPY ./flag/readflag.c /readflag.c
COPY ./flag/flag.txt /flag.txt
RUN chmod 0400 /flag.txt && chown root:root /flag.txt
RUN chmod 0444 /readflag.c && gcc /readflag.c -o /readflag
RUN chown root:root /readflag && chmod 4555 /readflag

WORKDIR /app

COPY publish/VArchive /app
COPY publish/youtube-dl /app

RUN mkdir /archives
RUN chown root:root /archives && chmod 1777 /archives
RUN chown -R root:root /app && chmod -R 0555 /app

USER nobody

ENV DOTNET_SYSTEM_GLOBALIZATION_INVARIANT 1
CMD /app/VArchive
