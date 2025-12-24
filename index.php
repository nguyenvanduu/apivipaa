<?php

// API gốc
$apiUrl = 'https://wtx.tele68.com/v1/tx/sessions';

// Gọi API bằng cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Giải mã JSON gốc
$data = json_decode($response, true);

// Mảng kết quả đầu ra
$output = [];

// Giới hạn tối đa 50 dòng
$maxItems = 50;
$count = 0;

// Duyệt qua danh sách và chỉ lấy 50 phần tử đầu
foreach ($data['list'] as $item) {
    if ($count >= $maxItems) {
        break;
    }

    // Lấy thông tin phiên, điểm, xúc xắc và kết quả
    $session = $item['id'];
    $dice = $item['dices']; // Mảng gồm 3 phần tử
    $total = $item['point'];
    $result = ($item['resultTruyenThong'] === 'TAI') ? 'Tài' : 'Xỉu';

    // Tạo mảng mới đúng định dạng yêu cầu
    $output[] = [
        'Ket_qua' => $result,
        'Phien' => $session,
        'Tong' => $total,
        'Xuc_xac_1' => $dice[0],
        'Xuc_xac_2' => $dice[1],
        'Xuc_xac_3' => $dice[2],
        'id' => 'huylamtool'
    ];

    $count++;
}

// Xuất kết quả JSON nằm ngang
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);