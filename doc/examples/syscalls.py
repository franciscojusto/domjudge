import os

def run(cmd):
    print "Running ", cmd
    p = os.popen(cmd,"r")
    while 1:
        line = p.readline()
        if not line: break
        print line
    p.close()

run('touch /hax')
run('ls')
run('pwd')
run('echo "lol" > hax')
