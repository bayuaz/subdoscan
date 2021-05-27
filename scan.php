<?php

error_reporting(0);

function banner() {
	include 'warna.php';

	echo $hijau;
	echo "   ____     __      __                _\n";
	echo "  / __/_ __/ /  ___/ /__  __ _  ___ _(_)__\n";
	echo " _\ \/ // / _ \/ _  / _ \/  ' \/ _ `/ / _ \ \n";
	echo "/___/\_,_/_.__/\_,_/\___/_/_/_/\_,_/_/_//_/\n";
	echo "  / __/______ ____  ___  ___ ____\n";
	echo " _\ \/ __/ _ `/ _ \/ _ \/ -_) __/\n";         
	echo "/___/\__/\_,_/_//_/_//_/\__/_/\n\n";

	echo $merah;
	echo "    Coded by Khatulistiwa";
	echo $clear;     

	menu();
}

function menu() {
	include 'warna.php';

	echo "\n\n{$cyan}1. Single List";
	echo "\n2. Multi List";
	echo "\n\n{$kuning}Masukkan pilihan: ";
	$pilih = trim(fgets(STDIN));

	if ($pilih == 1) {
		singleList();
	} else if ($pilih == 2) {
		multipleList();
	} else {
		echo "\n{$merah}Pilihan anda tidak valid!{$clear}\n";
	}
}

function singleList() {
	include 'warna.php';

	echo "URL (ex: site.com): ";
	$input = trim(fgets(STDIN));

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, "https://sonar.omnisint.io/subdomains/$input");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch);

	$hasil = json_decode($output, true);
	echo "\n$clear";

	if (is_null($hasil)) {
		echo "{$merah}Wrong input or site doesn't have subdomain!{$clear}\n";
	} else {
		foreach ($hasil as $no => $list) {
		    echo $no+1 . ". $list\n";
		    $fp = fopen("./result/single/$input.txt", 'a+');
			fwrite($fp, "$list\n");
			fclose($fp);
		}

		echo "\n{$cyan}Saved in ./result/single/$input.txt!{$clear}\n";
	}
}

function multipleList() {
	include 'warna.php';

	echo "Masukkan list: ";
	$input = trim(fgets(STDIN));
	$site = explode(PHP_EOL, file_get_contents($input));

	if (!file_exists($input)) {
		die("\n{$merah}File tidak ada!{$clear}\n");
	} else {
		foreach ($site as $no => $target) {
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "https://sonar.omnisint.io/subdomains/$target");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch);

			$hasil = json_decode($output, true);
			echo "$clear\n";

			if (is_null($hasil)) {
				echo "{$merah}$target : site doesn't have subdomain!{$clear}\n";
			} else {
				echo "{$clear}" . ++$no . ". $target ==> ".count($hasil)." subdomains {$cyan}(Saved in ./result/multiple/$target.txt!){$clear}";

				foreach ($hasil as $list) {
				    $fp = fopen("./result/multiple/$target.txt", 'a+');
					fwrite($fp, "$list\n");
					fclose($fp);
				}
			}
		}

		echo "\n";
	}
}

banner();