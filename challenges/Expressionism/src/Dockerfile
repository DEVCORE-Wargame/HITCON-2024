# Stage 1: Build the application
FROM maven:3.6.3-jdk-8 AS build
WORKDIR /app
COPY pom.xml .
COPY src ./src
RUN mvn clean package -DskipTests

# Stage 2: Deploy to Tomcat
FROM tomcat:9-jdk21
WORKDIR /usr/local/tomcat/webapps/
COPY --from=build /app/target/expressionism-0.0.1-SNAPSHOT.war ./ROOT.war

RUN groupadd -r tomcat && useradd -r -g tomcat tomcat
RUN chown -R root:root /usr/local/tomcat/
RUN chmod -R 777 /usr/local/tomcat /usr/local/tomcat/temp /usr/local/tomcat/work /usr/local/tomcat/logs /usr/local/tomcat/webapps
RUN chmod +t /usr/local/tomcat /usr/local/tomcat/temp /usr/local/tomcat/work /usr/local/tomcat/logs /usr/local/tomcat/webapps

USER tomcat

# Expose port 8080
EXPOSE 8080

CMD ["catalina.sh", "run"]
