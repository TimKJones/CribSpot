import simplejson as json
import sys

def main():
    try:
        infile = sys.argv[1]
        outfile = sys.argv[2]
    except:
        print "Syntax Error: format is uni_json_to_sql.py inputfile outfile"
    f = open(infile)
    data = json.loads(f.readline())
    f.close()

    outfile = open(outfile, 'w')
    counter = 0
    for u in data:
        if 'geocoder' in u:
            query = "INSERT into universities (name, city, state, domain, latitude, longitude, geocoder) VALUES ('{0}', '{1}', '{2}', '{3}', '{4}', '{5}', '{6}');".format(u['name'], u['city'], u['state'], u['domain'], u['latitude'], u['longitude'], json.dumps(u['geocoder']).replace("'", "''"))  # replace all the interal ' with '' to sql doesn't get mad and escapes them correctly
        else:
            query = "INSERT into universities (name, city, state, domain, latitude, longitude, geocoder) VALUES ('{0}', '{1}', '{2}', '{3}', null, null, null);".format(u['name'], u['city'], u['state'], u['domain'])
        outfile.write(query + '\n')
        counter += 1

    outfile.close()
    print "Processed " + str(counter) + " queries"



if __name__ == "__main__":
    main()
