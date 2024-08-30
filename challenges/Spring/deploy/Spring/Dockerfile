# Stage 1: Build the application
FROM maven:3.8.5-openjdk-17 AS build
WORKDIR /app
COPY pom.xml .
COPY src ./src
RUN mvn clean package -DskipTests

# Stage 2: Deploy to Tomcat
FROM tomcat:9.0.91-jdk17
WORKDIR /usr/local/tomcat/webapps/
COPY --from=build /app/target/spring-0.0.1-SNAPSHOT.war ./ROOT.war

RUN apt update && apt install -y gcc
COPY ./flag/readflag.c /readflag.c
COPY ./flag/flag.txt /flag.txt
RUN chmod 0400 /flag.txt && chown root:root /flag.txt
RUN chmod 0444 /readflag.c && gcc /readflag.c -o /readflag
RUN chown root:root /readflag && chmod 4555 /readflag

# Switch to 'www-data' user
RUN chown -R www-data:www-data /usr/local/tomcat/webapps/
USER www-data

# Expose port 8080
EXPOSE 8080

CMD ["catalina.sh", "run"]
