import urllib.parse,urllib.request,sys
import json
import webbrowser
import os

#from urllib import parse
ip = "192.168.10.4"
base = "http://"+ip+"/Barber/public/api/"
token = "$2y$10$Zik4nXR7/RqIjpFxChRrsufE.0hCuWKrkFKLyV.oD8CtTtw5e4br2"
token = urllib.parse.quote(token, safe='')
url = sys.argv[1]
isWeb = sys.argv[2]
isWeb = isWeb.lower()
# url = urllib.parse.quote(url)
url = base+url+"token="+token
# print(url)
try:
    os.system("./clearme.sh")
    req = urllib.request.urlopen(url)
    read = req.read().decode('utf-8')
    js = json.loads(read)
    os.system("clear")
    print("======================== \n")
    print("Status: "+str(req.status))
    print("======================== \n")
    print(js)
    print("***************************** \n")
except:
    print("error")
# print(read)
# print(req.read().decode('utf-8'))
# j = json.dumps(read)


if(isWeb=="web"):
    webbrowser.open(url,new=2)


