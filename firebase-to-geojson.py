#!/usr/bin/python3

from geojson import FeatureCollection, Feature, Point
import requests
import json
import time
import random

firebaseurl = "https://a-totally-valid-firebase-account.firebaseio.com/hitmap.json"
geocodeurl = "https://geocode.xyz/"
geocodeapi = "?json=1"
geofeatures = []
geocodeurl = 'https://maps.googleapis.com/maps/api/geocode/json'
geocodeparams = {'key':'lolyeahright','sensor': 'false'}


def getlatlon(city):
        geocodeparams['address'] = city
        r = requests.get(geocodeurl, params=geocodeparams)
#       print(r.text)
        results = r.json()['results']
#       if (results is not None):
        location = results[0]['geometry']['location']
        lat = (location['lat'])
        lon = (location['lng'])
        return float(lon),float(lat)


def buildgeojson(city, ip, asn, org, isp, services):
        lon, lat = getlatlon(city)
        if lon:
                if org == isp:
                        myorg = org
        #else:
                myorg = org + " --- " + isp
                print(lat,lon,myorg,city,ip,asn)
                lonjitter = random.uniform(-0.0003,0.0003)
                latjitter = random.uniform(-0.0003,0.0003)
                thislon = lon+float(lonjitter)
                thislat = lat+float(latjitter)
                gjpoint = Point((thislon, thislat))
                geofeatures.append(Feature(geometry=gjpoint,properties={'city':str(city),'ip':str(ip),'asn':str(asn),'org_isp':str(myorg),'services':str(services)}))
#               time.sleep(1)


def eachjson(server,ip):
        #this function ensures that city data is present in the json, its easier to break it out this way.
        myinfo = jason[server][ip]
        if myinfo and 'city' in myinfo:
                if 'isp' in myinfo:
                        #print(str(myinfo['city']))
                        buildgeojson(str(myinfo['city']),str(myinfo['ip']),str(myinfo['asn']),str(myinfo['org']),str(myinfo['isp']),str(myinfo['service-data']))
#                       time.sleep(1)

#main starts here
resp = requests.get(firebaseurl)
print(resp)
jason = json.loads(resp.text)
for x in jason:
        for y in jason[x]:
                eachjson(x,y)

feature_collection = FeatureCollection(geofeatures)
with open('/var/www/html/maptastic/mb-hitmap.geojson', 'w+') as f:
        f.write(str(feature_collection))
