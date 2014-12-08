use LWP::UserAgent;

my $lwp = LWP::UserAgent->new(agent=>' Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0', cookie_jar=>{});



for($i=0;$i<=1000;$i++){
	my $link = 'http://everquest.allakhazam.com/spellicons/gem_' . $i . 'b.png';
	if($link){
		my $resp = $lwp->mirror($link, 'gem_' . $i . 'b.png');
		print "Downloading " . $i . "\n";
	}
}

unless($resp->is_success) {
    print $resp->status_line;
}