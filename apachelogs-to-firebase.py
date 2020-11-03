#!/usr/bin/python3
#curl -X POST -d '{"user_id" : "jack", "text" : "Ahoy!"} 'https://[PROJECT_ID].firebaseio.com/message_list.json'

import re, os, sys, requests, json, time
import shodan
import pprint

filepath = '/var/log/apache2/access.log'
api = shodan.Shodan("nowaybobbyflay")
testarray = []
telephone = {"no-info-for-host":"---none---"}
service = 'https://super-nifty-realtime-db.firebaseio.com/hitmap'
pp = pprint.PrettyPrinter(indent=3)
thisservername = "SEVERANCE82"

def runNudeTayne(hatwobble):
        try:
                hatarray = []
                singlehost = api.host(hatwobble)
                hatorg = singlehost.get('org', 'n/a')
                hatos = singlehost.get('os', 'n/a')
                #for item in singlehost.get('location','n/a'):
                hatcity = singlehost.get('city','n/a')
                hatcountry = singlehost.get('country','n/a')
                hatcountrycode = singlehost.get('country_code','n/a')
                hatisp = singlehost.get('isp','n/a')
                hatasn = singlehost.get('asn','n/a')
                for item in singlehost['data']:
                        hatport = str(item['port'])
                        hatdata = str(item['data'].encode('utf-8'))
                        cleanhatdata = hatdata.replace(":","-") #get rid of :'s in the json port dat
                        hatarray.append({"port":hatport,"portinfo":cleanhatdata})
                chonker = {"ip":hatwobble,"org":hatorg,"os":hatos,'city':hatcity,'country':hatcountry,'countrycode':hatcountrycode,'isp':hatisp,'asn':hatasn, "service-data":hatarray}
                time.sleep(1)
                return chonker
        except shodan.APIError:
                return telephone

def doTheThing(id):
        cleandata = json.dumps(runNudeTayne(id))
        myurl = service+"/"+thisservername+"/"+str(id).replace(".","d")+".json"
        myurl2 = myurl.replace("<","(")
        myurl3 = myurl2.replace("{",";")
        myurl4 = myurl3.replace("\\","\/")

        #the firebase sender is below
        chonker = requests.patch(myurl4, data=cleandata)
        thisdata = json.loads(cleandata)

        #debug print below, also for creating visitor site
        if len(thisdata) > 1:
                print("<BR><BR><BR><BR>---")
                print("<BR>IP Address: "+str(thisdata["ip"]))
                print("<BR>Organizaion: "+str(thisdata["org"]))
                print("<BR>OS: "+str(thisdata["os"]))
                print("<BR>ASN: "+str(thisdata["asn"]))
                print("<BR>City: "+str(thisdata["city"]))
                print("<BR>Country: "+str(thisdata["country"]))
                print("<BR>CC: "+str(thisdata["countrycode"])+"<BR>")
                print(str(pp.pprint(thisdata["service-data"])))


def getTheStuff(split_line):
        split_line = line.split()
        return split_line[0]


with open(filepath) as fp:
        line = fp.readline()
        cnt = 1
        while line:
                thisline = getTheStuff(line.strip())
                testarray.append(thisline)
                line = fp.readline()
                cnt += 1

for x in set(testarray):
        doTheThing(x)
