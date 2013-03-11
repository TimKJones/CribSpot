from bs4 import BeautifulSoup
from array import *
import os
import urllib
import re
from HTMLParser import HTMLParser

f = open("Listings.aspx","r")
html = f.read()

#url = 'http://www.campusmgt.rentlinx.com/Listings.aspx';
#urllib.urlretrieve(url)

soup = BeautifulSoup(html)
addresses = [];
types = [];
beds = [];
units = [];
rents = [];

for link in soup.find_all('a'):
	if (re.match("[0-9]+.*", link.text.strip()) != None):
		addresses.append(link.text.strip());

for link in houses:
	print link

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

