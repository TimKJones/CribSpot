#!/usr/bin/python
import simplejson as json
import requests

f = open('v4.json')
data = json.loads(f.readline())
log = open('log.txt', 'w')
failures = 0
old_entries = 0
new_entries = 0

for university in data:

    if 'geocoder' in university:
        log.write('[Geo Data Already Exists] ' + str(university['id']) + ' ' + university['name'] + '\n')

        old_entries += 1
        print 'old'
    else:
        location = university['name'] + ' ' + university['city'] + ' ' + university['state']
        query = location.replace(" ", "+")
        url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' + query + '&sensor=false'
        r = requests.get(url)
        json_response = json.loads(r.text)

        if json_response['status'] == 'OK':
                location = json_response['results'][0]['geometry']['location']
                university['latitude'] = location['lat']
                university['longitude'] = location['lng']
                university['geocoder'] = json_response
                log.write("[Data Found] " + str(university['id']) + ' ' + university['name'] + '\n')
                new_entries += 1
                print 'new'

        else:
            log.write("[Failed To Find] " + str(university['id']) + ' ' + university['name'] + ' ' + query + '\n')
            failures += 1
            print 'fail'

out = json.dumps(data)
out_file = open("out.txt", "w")
out_file.write(out)
out_file.close()
log.close()

print "Old Entries: " + str(old_entries) + " New Entries: " + str(new_entries) + " Failures: " + str(failures)
print "done"

def main():
    if __name__ == "__main__":
        main()
