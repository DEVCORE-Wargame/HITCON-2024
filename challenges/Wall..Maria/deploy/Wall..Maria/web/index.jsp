<%
    String file = request.getParameter("file");

    if (
      file == null || file.isEmpty()
      || !request.getParameter("give").equals("me")
      || !request.getParameter("the").equals("flag")
    ) {
      %><script>window.location.href = "/?give=me&the=flag&file=greet";</script><%
    } else {
        try (java.io.FileInputStream fis = new java.io.FileInputStream("./webapps/ROOT/" + file)) {
            byte[] buffer = new byte[4096];
            int n;
            while ((n = fis.read(buffer)) != -1) response.getOutputStream().write(buffer, 0, n);
        } catch (Exception e) {
            response.sendError(404, e.getMessage());
        }
    }
%>
