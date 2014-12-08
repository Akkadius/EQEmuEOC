#!/usr/bin/perl

use LWP::Simple;
 
for($i = 5000; $i<= 10000; $i++){
	print getstore("http://everquest.allakhazam.com/pgfx/item_" . $i . ".png", "item_" . $i . ".png");
}