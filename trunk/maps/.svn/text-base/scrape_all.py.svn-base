from bs4 import BeautifulSoup
from array import *
import os
import urllib
import re
from HTMLParser import HTMLParser


#url = 'http://www.campusmgt.rentlinx.com/Listings.aspx';
#urllib.urlretrieve(url)

all_td = [];
addresses = [];
types = [];
beds = [];
units = [];
rents = [];

f = open("all_td","r")
all_td = f.read()

num_lines = 0

next_line = ""
lines = []

for char in all_td:
	if char != "\n":
		next_line += char;
	else:
		num_lines += 1
		lines.append(next_line.strip())
		next_line = ""

i = 0
while i  < num_lines: 
	if (re.match("[0-9]+\s[A-z]+", lines[i]) != None):
		addresses.append(lines[i]);
	i += 1

for address in addresses:
	print address

#for link in addresses:
#	print link

#class MyHTMLParser(HTMLParser):
#	def handle_starttag(self, tag, attrs):
#    	self.output = ""
 #   def handle_endtag(self, tag):
  #      print output; 
#    def handle_data(self, data):
#        if re.match("?<=[0-9]).*", "311", re.DOTALL):
#			print data
#parser = MyHTMLParser()
#parser.feed(html);

