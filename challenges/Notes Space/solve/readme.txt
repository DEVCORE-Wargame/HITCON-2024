Run httpsrv.py & ftpsrv.py, submit http://[blog]/ftp://url/to/0000-00-00-stage1

How?
    1. [Blog XSS] set cookie to 'PHPSESSID=my_session_id'
        - xss on [blog] to set cookie
        - go to [app]/login.php to create session
    2. [App SQLi + php://] leak the csrf token
    3. [App CSRF -> XSS] get flag (with PHPSESSID=my_session_id + leaked csrf token)


