import requests
import simplejson as json


def main():
    schools = {}
    f = open('uni.txt')
    index = 0
    for line in f:
        if line.isspace():
            continue
        tmp = line.rfind(" ")
        name = line[:tmp]
        domain = line[(tmp+1):].replace("\n", "")

        r = requests.get('http://maps.googleapis.com/maps/api/geocode/json?address='+ name.replace(" ", "+") + '&sensor=false')
        json_response = json.loads(r.text)
        # If the geocoder response okay, snag out the city state and country
        if json_response['status'] == 'OK':
            addr_comp = json_response['results'][0]['address_components']
            num_fields = len(addr_comp)
            postal = addr_comp[num_fields-1]['long_name']

            if not len(postal) == 5:
            	num_fields-=1
            	postal = addr_comp[num_fields-1]['long_name']

            country = {
                'long_name': addr_comp[num_fields-2]['long_name'],
                'short_name': addr_comp[num_fields-2]['short_name']
            }

            state = {
                'long_name': addr_comp[num_fields-3]['long_name'],
                'short_name': addr_comp[num_fields-3]['short_name']
            }

            city = {
                'long_name': addr_comp[num_fields-4]['long_name'],
                'short_name': addr_comp[num_fields-4]['short_name']
            }

            formatted_addr = json_response['results'][0]['formatted_address']

        schools[index] = {
            'name': name,
            'domain': domain,
            'postal': postal,
            'country': country,
            'state': state,
            'city': city,
            'formatted_address': formatted_addr
        }
        # print schools[index]
        index += 1
        print name

    out = json.dumps(schools)
    out_file = open("out.txt", "w")
    out_file.write(out)
    out_file.close()
    print "wrote data to file"
    # print a
    # r = requests.get('http://maps.googleapis.com/maps/api/geocode/json?address='+ schools[0]['name'].replace(" ", "+") +'&sensor=false')
    # json_response = json.loads(r.)



if __name__ == "__main__":
    main()
