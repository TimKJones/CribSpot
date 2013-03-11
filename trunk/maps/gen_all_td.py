from bs4 import BeautifulSoup
from array import *
import os
import urllib
import re
from HTMLParser import HTMLParser
import urllib2
from geopy import geocoders

#NOTE:  0 bedrooms means it is a STUDIO
#		rent = 0 means it does not list a rent; instead says "leased until XX/XX/XXXX
#------------------------------------------ Utility Functions  --------------------------------------------------------
#returns True if line is a street address
def isAddress(line):
    if re.match("[0-9]+\s[A-z]+", line.strip()) != None:
		if len(line.strip()) <= 30 and len(line.strip()) > 2:
			if not "337 East Huron Street" in line:
				return True;
			else:
				return False;
		else:
			return False;
    else:
        print line.strip();
        return False;

#parses line of the form (x available) and returns x
def numUnitsAvailable(line):
	if line[0] != "(":
		return line
	ret = ""
	i = 1
	while line[i] != "a":
		ret += line[i]
		i += 1
	return int(ret)			

#returns true if the current field is formatted like a date
def isDate(line):
	if re.match("[0-9]+/[0-9]+/[0-9]+", line.strip()) != None or line.strip() == "Yesterday" or line.strip() == "Today":
		return True
	return False

#returns the minimum number of bedrooms
def minBedrooms(line):
	ret = ""
	i = 0
	while i < len(line) and line[i] != "-" and line[i] != " ":
		ret += line[i]
		i += 1
	if ret == "Studio":
		return 0;		
	return int(ret)

#returns the minimum number of bedrooms
def maxBedrooms(line):
	i = 0
	while i != len(line) and line[i] != "-":
		i += 1

	if i == len(line):
		return minBedrooms(line);

	i += 1
	while line[i] == " ":
		i += 1
	
	return int(line[i:])

#returns an integer rent or 0 if the rent is not listed (instead says "leased until XX/XX/XXXX"
def getRent(line):
	if line[0] == "$":
		return line[1:].strip().replace(",", "");
	else:
		return 0;

#returns latitude
#input: output from geopy geocode function
def getLat(addr):
    latLng = addr[1]
    return latLng[0]

#returns longitude
def getLong(addr):
    latLng = addr[1]
    return latLng[1]

#------------------------------------------- List Declarations ---------------------------------------------------------
all_td = [];
addresses = [];
types = [];
min_beds = [];
max_beds = [];
units = [];
units_avail = [];
rents = [];
#-----------------------------------------------------------------------------------------------------------

#get html source 
for i in range(5):

    if i == 0:
        url = 'http://www.campusmgt.rentlinx.com/Listings.aspx' # write the url here
    else:
        url = 'http://www.campusmgt.rentlinx.com/Listings.aspx?StartAt=' + str(i * 20) # write the url here
    
    usock = urllib2.urlopen(url)
    html = usock.read()
    usock.close()

    soup = BeautifulSoup(html)

    #url = 'http://www.campusmgt.rentlinx.com/Listings.aspx';
    #urllib.urlretrieve(url)

    #append all text between <td> tags onto all_td list
    metadata = soup.findAll('td');
    for data in metadata:
        for tmp in data.findAll(text=True):
            if (tmp.strip() != ""):
                all_td.append(unicode(tmp).encode("utf-8"));

    outfile = open("all_td","w")

    for td in all_td:
        outfile.write(td);

    outfile.close();
    infile = open("all_td", "r");

    lines = []

    for line in infile:
        if re.match(".+", line.strip()) != None:
            lines.append(line.strip())
    infile.close();
    i = 0

    #contains property type, beds, units, units available, updated date, rent
    #OR 
    #contains beds, units, units available, updated date, rent, description:w

    cur_house_data = []

    for i in range(len(lines)):
        if isAddress(lines[i]) and lines[i] not in addresses:
            addresses.append(lines[i]);
            print "yes: " + lines[i];
            #skip the Ann Arbor, MI 48104 line
            i += 2
            for j in range(6):
                if i < len(lines):
                    cur_house_data.append(lines[i]);
                    i += 1
#    		print "------------------ cur house data -------------------------"
    		#for line in cur_house_data:
    		#	print line
#    		print str(len(cur_house_data));
#            print "-----------------------------------------------------------"
            
            next_ind = 0
            #if len(cur_house_data) < 5: 
            #    print "---------------- cur house data --------------------------"
            #    for data in cur_house_data:
            #        print data
            #    print "--------------------------------------------------------="

            if isDate(cur_house_data[4]):
                #property type field exists
                types.append(cur_house_data[0])
                next_ind +=1
            else:
                types.append("NONE")
                i -=1
            min_beds.append(minBedrooms(cur_house_data[next_ind]));
            max_beds.append(maxBedrooms(cur_house_data[next_ind]));
            units.append(cur_house_data[next_ind+1]);				
            units_avail.append(numUnitsAvailable(cur_house_data[next_ind+2]));
            rents.append(getRent(cur_house_data[next_ind+4]));
            cur_house_data = []	

    #get latitude and longitude coordinates
    latitudes = []
    longitudes = []

    for i in range(len(addresses)):
        latitudes.append(0);
        longitudes.append(0);

    for i in range(len(addresses)):
        print "address: " + addresses[i]
        print "type: " + types[i]
        print "min_beds: " + str(min_beds[i])
        print "max_beds: " + str(max_beds[i])
        print "units: " + units[i]
        print "units available: " + str(units_avail[i])
        print "rents: " + str(rents[i])
        print "latitude: " + str(latitudes[i])
        print "longitude: " + str(longitudes[i])

    outfile = open("insert1.sql","w")

    for i in range(len(addresses)):
        outfile.write("insert into ubid.Houses (address, latitude, longitude, unit_type, minBeds, maxBeds, units, units_avail, rent, company, bathrooms, availableMonth, avilableYear) VALUES ('" + addresses[i] + "', '" + str(latitudes[i]) + "', '" + str(longitudes[i]) + "', '" + types[i] + "', '" + str(min_beds[i]) + "', '" + str(max_beds[i]) + "', '" + str(units[i]) + "', '" + str(units_avail[i]) + "', '" + str(rents[i]) + "', 'Campus Management', '-1', 'NA', 'NA');\n");



