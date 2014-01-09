#!/bin/bash

# Program to set up ssh on port 4567 for PM login tool
# 

SSH_DIR=~/.ssh # Path of the ssh directory to install

# Check if the directory already exists
# Otherwise create the directory
if [ ! -d "$SSH_DIR" ]; then
  sudo mkdir "$SSH_DIR" # Create the directory
	sudo chown root -R "$SSH_DIR" # Lock the directory to the root user
fi

## Check if the Cribspot.pem has been moved to the ssh directory
# Moves the pem file and restricts users to root
if [ ! -f "$SSH_DIR"/Cribspot.pem ]; then
	sudo cp Cribspot.pem "$SSH_DIR"
	sudo chown root "$SSH_DIR"
	# sudo rm Cribspot.pem
fi

# Change to the directory
cd "$SSH_DIR"

# We made the directory or it already existed
# If we cannot get there, error out
if [ `pwd` != "$SSH_DIR" ]; then
	echo "There has been a problem bro"
	exit 1
fi

if [ "$(uname)" == "Darwin" ]; then
	open 'http://localhost:8080/Users/PMAdmin' &
elif [ "$(expr substr $(uname -s) 1 5)" == "Linux" ]; then
	xdg-open 'http://localhost:8080/Users/PMAdmin' &
fi

# Connect via ssh to Cribspot.com
sudo ssh -i "$SSH_DIR"/Cribspot.pem ubuntu@cribspot.com -L8080:localhost:80
# "nohup /usr/bin/open 'http://google.com/' > /dev/null 2>&1 &"
