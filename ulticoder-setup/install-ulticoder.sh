#!/bin/bash

echo "Installing ulticoder"

# Speed up script, update only if necessary
if [ ! -f ".ulticoder_system_updated" ]; then
  sudo apt-get update
  sudo apt-get -y upgrade
  sudo apt-get -y install aptitude git curl
  touch ".ulticoder_system_updated"
fi

# Judgehost requirements
sudo apt-get -y install make sudo php5-cli php5-curl php5-json procps \

# Compilers
sudo apt-get -y install gcc g++ openjdk-6-jre-headless openjdk-6-jdk ghc fp-compiler mono-gmcs

# Submit client requirements
sudo apt-get -y install libcurl4-gnutls-dev libjsoncpp-dev libmagic-dev

sudo apt-get -y install gcc g++ make libcurl4-gnutls-dev mysql-server \
        apache2 php5 php5-cli libapache2-mod-php5 php5-mysql php5-json \
        php-geshi phpmyadmin \
        ntp sudo procps xsltproc \
        libboost-regex-dev libgmp3-dev linuxdoc-tools linuxdoc-tools-text \
        transfig groff texlive-latex-recommended texlive-latex-extra \
        texlive-fonts-recommended

sudo apt-get -y install make sudo php5-cli php5-mysql php5-common ntp xsltproc procps

sudo apt-get -y install autoconf automake flex flexc++ bisonc++ linuxdoc-tools-info linuxdoc-tools-latex$

sudo apt-get -y install libjsoncpp-dev

pushd $HOME
git clone https://github.com/franciscojusto/domjudge.git

echo "Entering to domjudge directory"
cd domjudge
sudo make dist
sudo make maintainer-conf

sudo ./configure --prefix=$HOME/domjudge

sudo make domserver
sudo make install-domserver
sudo make judgehost
sudo make install-judgehost
sudo make docs
sudo make install-docs
sudo make submitclient

sudo ./domserver/bin/dj-setup-database -u root -r install 

sudo ln -s $HOME/domjudge/domserver/etc/apache.conf /etc/apache2/conf.d/domjudge.conf

echo "ServerName localhost" | sudo tee /etc/apache2/conf.d/fqdn

sudo apache2ctl graceful

sudo cp /etc/apache2/sites-available/default /etc/apache2/sites-available/ulticodersite

OLD="var/www"
OLD="${OLD//\//\\/}"
NEW="$HOME/domjudge/main-www"
NEW="${NEW//\//\\/}"

sudo sed -i "s/${OLD}/${NEW}/g" /etc/apache2/sites-available/ulticodersite

sudo a2ensite ulticodersite
sudo service apache2 restart

sudo a2dissite default
sudo service apache2 restart

sudo chmod 777 main-www/

#\\ configuring judgehosts \\

# Install chroot
cd ~/domjudge/judgehost/bin
sudo sed -i -e 's#SCALAPKG=".*"#SCALAPKG="http://www.scala-lang.org/files/archive/scala-2.10.3.tgz"#' dj_make_ubuntu_chroot
sudo ./dj_make_ubuntu_chroot /chroot i386

cd ~/domjudge/judgehost/etc/
sudo sed -i -e "s#define('CHROOT_SCRIPT', '');#define('CHROOT_SCRIPT', 'chroot-startstop.sh');#" judgehost-config.php

cd ~/domjudge/judgehost/lib/judge/
sudo sed -i 's/CHROOTORIGINAL=".*"/CHROOTORIGINAL="\/chroot"/g' chroot-startstop.sh

#create the restapi.secret file
#username: judgehosts
#password: password
#assume that the user has created the judgehost user with the following username#and password
cd ~/domjudge/judgehost/etc/
echo 'localhost/domjudge/api/ judgehosts password' | sudo tee -a restapi.secret
sudo useradd -d /nonexistent -g nogroup -s /bin/false domjudge-run

#set the number of cores
cd ~/domjudge/etc/
sudo sed -i -e "s#cpuset.cpus = 0#cpuset.cpus = 0-2#" cgroup-domjudge.conf

sudo service apache2 restart
#\\ finish judgehosts \\

# Cleanup
popd
rm ".ulticoder_system_updated" 2> /dev/null

echo "Install finished."
