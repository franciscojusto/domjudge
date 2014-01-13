#!/bin/bash

echo "Installing ulticoder"

sudo apt-get update
sudo apt-get upgrade
sudo apt-get install aptitude
sudo apt-get install git 

sudo git clone https://github.com/franciscojusto/domjudge.git

apt-get install gcc g++ make libcurl4-gnutls-dev mysql-server \
        apache2 php5 php5-cli libapache2-mod-php5 php5-mysql php5-json \
        php-geshi phpmyadmin \
        ntp sudo procps xsltproc \
        libboost-regex-dev libgmp3-dev linuxdoc-tools linuxdoc-tools-text \
        transfig groff texlive-latex-recommended texlive-latex-extra \
        texlive-fonts-recommended

apt-get install make sudo php5-cli php5-mysql php5-common ntp xsltproc procps

apt-get install gcc g++ openjdk-6-jre-headless openjdk-6-jdk ghc fp-compiler

sudo apt-get install autoconf automake flex flexc++ bisonc++ linuxdoc-tools-info linuxdoc-tools-latex$

echo "Entering to domjudge directory"
cd domjudge
sudo make dist
sudo make maintainer-conf

./configure --prefix=$HOME/domjudge

sudo make domserver
sudo make install-domserver
sudo make judgehost
sudo make install-judgehost
sudo make docs
sudo make install-docs

sudo apt-get install libjsoncpp-dev

#Add something
#Sudo make submitclient

sudo ./domserver/bin/dj-setup-database -u root -r install 

sudo ln -s $HOME/domjudge/domserver/etc/apache.conf /etc/apache2/conf.d/domjudge.conf

echo "ServerName localhost" | sudo tee /etc/apache2/conf.d/fqdn

sudo apache2ctl graceful

sudo cp /etc/apache2/sites-available/default /etc/apache2/sites-available/ulticodersite

OLD="var/www"
OLD="${OLD//\//\\/}"
NEW="home/sang/domjudge/main-www"
NEW="${NEW//\//\\/}"

sed -i "s/${OLD}/${NEW}/g" /etc/apache2/sites-available/ulticodersite

sudo a2ensite ulticodersite
sudo service apache2 restart

sudo a2dissite default
sudo service apache2 restart

sudo chmod 777 main-www/

