<?php

$green = "\e[32m";
$red = "\033[0;31m";
$clear = "\e[0m";

echo " $green
   ____     __      __                _    
  / __/_ __/ /  ___/ /__  __ _  ___ _(_)__ 
 _\ \/ // / _ \/ _  / _ \/  ' \/ _ `/ / _ \
/___/\_,_/_.__/\_,_/\___/_/_/_/\_,_/_/_//_/
  / __/______ ____  ___  ___ ____          
 _\ \/ __/ _ `/ _ \/ _ \/ -_) __/          
/___/\__/\_,_/_//_/_//_/\__/_/             

   $red Coded by Khatulistiwa $clear
";

echo "\nURL (ex: site.com): ";
$input = trim(fgets(STDIN));

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://sonar.omnisint.io/subdomains/".$input);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch); 
curl_close($ch); 

preg_match_all('/"(.*?)"/', $output, $hasil);
echo "\n";	

if (count($hasil[1]) == 0) {
	echo $red . "Wrong input or site doesn't have a subdomain! $clear \n";
} else {
	foreach($hasil[1] as $key => $list) {
	    echo $key+1 . ". " . $list . "\n";
	    $fp = fopen('result.txt', 'a+');
		fwrite($fp, $list."\n");
		fclose($fp);
	}

	echo "$green \nSaved in result.txt! $clear \n";
}