<%@ page contentType="text/html;charset=UTF-8" language="java" %>
<%@ taglib prefix="spring" uri="http://www.springframework.org/tags" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expressionism</title>
    <style>
        body {
            background-color: black;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
            font-weight: bold;
            font-size: 3rem;
            font-style: italic;
            font-family: monospace;
        }
        img {
            max-width: 20rem;
            height: auto;
        }
        .container {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="/static/The_Scream.jpg" alt="The Scream">
        <p><spring:message code="life.quotes.${id}" /></p>
    </div>
</body>
</html>
