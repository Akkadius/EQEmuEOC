# EQEmuEOC
EQEmu EOC - Rapid Development Platform

This platform is designed to help EQEmu Server developers develop content rapidly and mangage their server efficiently.

Tools Documentation: http://wiki.eqemulator.org/p?EQEmu_Operations_Center_for_Development#tools

Welcome to the EQEmuEOC wiki!
# Setting up a Dev Environment
* I have provided a convenient download to a development environment that can get you going in mere minutes. It is completely pre-configured with the following:

## Virtual Machine - VMWare (Player/Workstation)
* You will need one of the Type 2 Hypervisors, either will work just fine:
* [VMWare Player Download (Free)](https://my.vmware.com/web/vmware/free#desktop_end_user_computing/vmware_player/7_0)
* [VMWare Workstation Download (Not Free)](https://my.vmware.com/web/vmware/info/slug/desktop_end_user_computing/vmware_workstation/11_0)

![](http://i.imgur.com/OvWMfKv.png)

* Download: [EOC2 Dev VM Debian (2.2GB)](http://wiki.eqemulator.org/eoc2_dev_debian_7_8.rar)
* Debian 7.8
* MariaDB 10
* PHP5.5
* Apache
* PEQ Database :: User/Pass - root/eocdev
* OS User Credentials:
 * root/eocdev (Use this)
 * eocdev/eocdev
* Eth0 is set to DHCP on local LAN (Gets its own address)

## Booting Up
* The virtual machine takes seconds to boot up, once in the operating system you can login via (root/eocdev) and perform an 'ifconfig' to see the ip address that it has obtained from your local network.

![](http://i.imgur.com/qgV3RZ4.png)

## Browsing to EoC2
* Once you know what your address is you can browse to EOC, just punch in the IP address in your browser and you will be redirected to the repo folder

![](http://i.imgur.com/6P0aEmi.png)

![](http://i.imgur.com/29LznRL.png)

## Managing
* You can manage EOC VM via the VM Player or use SSH to the IP you found above in 'ifconfig'

## Git Repo
*  The Git Repo is already mounted to the web directory
** /var/www/EQEmuEOC/
* Immediately, you should be able to do a 'git pull' while you are in the directory to pull down the latest changes from the Git repository:
 * cd /var/www/EQEmuEOC/
 * git pull
* You should be updated once you have done this.

# Setting up Your Working Dev Environment

## FTP

* The server (VM) is already setup with FTP, and already customly setup so that you can login as the root user without having to deal with cumbersome permission issues when all the purpose of the box is to dev or local uses, we're not overly concerned with being secure.
 * You can hook any of your editors up to the server via FTP using the regular OS credentials:
 * root/eocdev

## Editors and IDE's
* The two editors I recommend:
 * Notepad++ and the FTP module, I've used this solution for a long time, however I have fallen in love with a full blown IDE solution such as:
 * PHPStorm IDE JetBrains: There are a lot of things to love about this IDE, and well worth the $50 personal license you end up paying after a 30-day trial. There were a few things that drove me nuts about using it at first being used to Notepad++, but there is hardly a thing that you can't customize to make it work for anyone.
* You are completely free to of course use anything else you are already familiar with using or install a GNOME desktop in the template VM and edit there.

## Editing with Notepad++
* Editing with Notepad++ is nice because of course its free and Notepad++ has a lot of rich features, but you are completely reliant upon navigating the file structure through an FTP interface and all of your data is simply pulled down for editing and then when you Ctrl + S, the Notepad++ FTP plugin will automatically upload your changes. For simple setups, it is easy to use this
* When you use this method of editing you have to perform your git commit and pull request/push commands on the server which is not a huge deal for those already familiar with doing so
 * Setup your connection (You may need to go to Plugins -> NppFTP -> Show FTP Window (Or install it too))
![](http://i.imgur.com/69uVo2e.png)
 * Connect to host
![](http://i.imgur.com/dWkOe9C.png)
 * Navigate to a file and start your editing!
![](http://i.imgur.com/LEwDQuQ.png)

## Using PHP Storm IDE
* There are so many powerful features to using this IDE that I can't even begin to explain what makes it just a dream to use. You can download it here: [PHPStorm Download](https://www.jetbrains.com/phpstorm/)
* I use FTP to connect this IDE as well, the main difference between PHPStorm and Notepad++ is that it pulls down an entire local copy from the VM of all the files so that everything can be loaded into intellisense and the IDE can intelligently perform re-factor oriented functions as well as many other functions.

### Install and Create a New Project
* Name it whatever you like, you will see like so:
![](http://i.imgur.com/qcJTpZ2.png)

### Hooking up FTP to the VM Repo
* Go to Tools -> Deployment -> Configuration
![](http://i.imgur.com/3MbFClL.png)

* Configure your server with your IP address, use your DHCP address you found above, or the static IP address you may have configured on this VM. Make sure you also set the connection as the default connection:

![](http://i.imgur.com/johProg.png)

* Your Mappings tab will look like the following:

![](http://i.imgur.com/5ifkqXV.png)

* In advanced check hidden files

![](http://i.imgur.com/NVfoMVG.png)

* You should be done at this point, you can go to Tools -> Deployment and then 'Download from (host)' to do a complete repo sync
* Make sure you go to Tools -> Deployment and also check 'Automatic Upload', this will make sure when you change any files it also updates the VM so you can see your immediate changes

### Hooking PHP Storm up to the Github Repo

* You should have had your git config pull down in the FTP sync, if not simply FTP to your VM and pull down the .git folder and place it in your PHP Storm project folder, this will make it so that the IDE is aware of your repository
* At this point you can go into your project settings and configure your project to use Github and also enter your Github credentials so it makes it really easy for your to commit and push without entering your password repeatedly

![](http://i.imgur.com/mQ0fwJB.png)

* You may need to reboot your IDE, but once everything is working correctly, you should have easy access Github commit/push and pull buttons at the top right:

![](http://i.imgur.com/vS19g3Q.png)