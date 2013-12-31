#!/bin/bash

# Program to set up ssh on port 9999 for PM login tool
# 

SSH_DIR=~/.ssh # Path of the ssh directory to install

# Check if the directory already exists
# Otherwise create the directory
if [ ! -d "$SSH_DIR" ]; then
  sudo mkdir "$SSH_DIR" # Create the directory
	sudo chown root -R "$SSH_DIR" # Lock the directory to the root user
fi

# Change to the directory
sudo cd "$SSH_DIR"

# We made the directory or it already existed
# If we cannot get there, error out
if [ `pwd` != "$SSH_DIR" ]; then
	echo "There has been a problem bro"
	exit 1
fi

# Check if the Cribspot.pem has been moved to the ssh directory
# Moves the pem file and restricts users to root
if [ ! -f "$SSH_DIR"/Cribspot.pem ]; then
	sudo cp Cribspot.pem "$SSH_DIR"
	sudo chown root "$SSH_DIR"
	sudo rm Cribspot.pem
fi

# Connect via ssh to Cribspot.com
sudo ssh -i "$SSH_DIR"/Cribspot.pem ubuntu@cribspot.com -L9999:localhost:80

# Check if google chrome is installed
command -v google-chrome >/dev/null 2>&1 || { echo >&2 "Please go to http://localhost:9999 in your favorite browser!"; exit 1; }

# Open google chrome
sudo google-chrome localhost:9999
