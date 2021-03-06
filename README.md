# Cloudy GNU/Linux Distribution 

### About
A community networking cloud in a box.

The *Cloudy GNU/Linux Distribution* turns your embedded, barebone or mini computer into a *community networking cloud in a box*.

### Installation
This repository contains the source code for the scripts, daemons and web GUI of Cloudy. To install Cloudy on your network computing device, use the automated installation script located at https://github.com/Clommunity/cloudynitzar.

## Testing with cloudynitzar.sh [![Build Status](https://travis-ci.org/Clommunity/cloudynitzar.svg?branch=master)](https://travis-ci.org/Clommunity/cloudynitzar)

### About Cloudynitzar
Cloudynitzar is a shell script that turns your plain Debian or Ubuntu system into a community networking cloud in a box (i.e. a full-featured Cloudy device). It might as well work on Debian and Ubuntu derivatives, like Linux Mint. Feel free to test and report!

### Requirements
*Recommended*: a fresh and updated Debian 9 *Stretch* installation with `curl`, `lsb-release` and an Internet connection.

Cloudynitzar will also work on a Debian 8 *Jessie* installation and may also work on a Debian 7.8 *Wheezy* with the *backports* repository enabled. It also supports Ubuntu 14.04 *Trusty* LTS, Ubuntu 16.04 *Xenial* LTS, Ubuntu 16.10 *Yakkety* and Ubuntu 17.04 *Zesty*.

### Procedure
From your Debian system run, as root:

````sh
apt-get update; apt-get install -y curl lsb-release
curl -k https://raw.githubusercontent.com/Clommunity/cloudynitzar/master/cloudynitzar.sh | bash -
````
### Learn more
You can find more information about Cloudy at https://www.cloudy.community and https://www.clommunity-project.eu.
