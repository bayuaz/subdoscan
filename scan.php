<?php

error_reporting(0);

include "warna.php";

function banner() {
	global $clear, $merah, $hijau;

	echo $hijau;
	echo "   ____     __      __                _" . PHP_EOL;
	echo "  / __/_ __/ /  ___/ /__  __ _  ___ _(_)__" . PHP_EOL;
	echo " _\ \/ // / _ \/ _  / _ \/  ' \/ _ `/ / _ \ " . PHP_EOL;
	echo "/___/\_,_/_.__/\_,_/\___/_/_/_/\_,_/_/_//_/" . PHP_EOL;
	echo "  / __/______ ____  ___  ___ ____" . PHP_EOL;
	echo " _\ \/ __/ _ `/ _ \/ _ \/ -_) __/" . PHP_EOL;         
	echo "/___/\__/\_,_/_//_/_//_/\__/_/" . PHP_EOL . PHP_EOL;

	echo $merah;
	echo "    Coded by Khatulistiwa";
	echo $clear;     

	menu();
}

function menu() {
	global $clear, $merah, $kuning, $cyan;

	echo PHP_EOL . PHP_EOL . "{$cyan}1. Single List";
	echo PHP_EOL . "2. Multi List";
	echo PHP_EOL . PHP_EOL . "{$kuning}Masukkan pilihan: ";
	$pilih = trim(fgets(STDIN));

	if ($pilih == 1) {
		singleList();
	} else if ($pilih == 2) {
		multipleList();
	} else {
		echo PHP_EOL . "{$merah}Pilihan anda tidak valid!{$clear}" . PHP_EOL;
	}
}

function singleList() {
	global $clear, $merah, $cyan;

	echo "{$clear}URL (ex: site.com): ";
	$input = trim(fgets(STDIN));

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, "https://sonar.omnisint.io/subdomains/$input");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch);

	$hasil = json_decode($output, true);
	echo PHP_EOL . "$clear";

	if (empty($hasil) || isset($hasil['error'])) {
		echo "{$merah}Wrong input or site doesn't have subdomain!{$clear}" . PHP_EOL;
	} else {
		foreach ($hasil as $no => $list) {
		    echo $no+1 . ". $list" . PHP_EOL;

		    # save result
		    $fp = fopen("./result/single/$input.txt", 'a+');
			fwrite($fp, "$list" . PHP_EOL);
			fclose($fp);
		}

		echo PHP_EOL . "{$cyan}Saved in ./result/single/$input.txt!{$clear}" . PHP_EOL;
	}
}

function multipleList() {
	global $clear, $merah, $hijau, $kuning, $cyan;

	echo "{$clear}Masukkan file list (ex: list.txt): ";
	$input = trim(fgets(STDIN));
	$site = explode(PHP_EOL, file_get_contents($input));

	if (!file_exists($input)) {
		die(PHP_EOL . "{$merah}File tidak ada!{$clear}" . PHP_EOL);
	} else {
		foreach ($site as $no => $target) {
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "https://sonar.omnisint.io/subdomains/$target");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$output = curl_exec($ch); 
			curl_close($ch);

			$hasil = json_decode($output, true);
			echo "$clear" . PHP_EOL;

			if (empty($hasil) || isset($hasil['error'])) {
				echo "{$clear}" . ++$no . ". {$target} {$kuning}--> {$merah}site doesn't have subdomain!{$clear}" . PHP_EOL;
			} else {
				echo "{$clear}" . ++$no . ". {$target} {$kuning}--> {$hijau}" . count($hasil) . " subdomains {$cyan}(Saved in ./result/multiple/$target.txt!){$clear}";

				foreach ($hasil as $list) {
					# save result
				    $fp = fopen("./result/multiple/$target.txt", 'a+');
					fwrite($fp, "$list" . PHP_EOL);
					fclose($fp);
				}
			}
		}

		echo PHP_EOL;
	}
}

banner();