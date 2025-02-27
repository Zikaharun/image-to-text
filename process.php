<?php
header('Content-Type: application/json');


$apikey = "AIzaSyC5w0SUZ4a5y8IOhaBeLFthb-l5KH6qSVM";

if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imagePath = $_FILES['image']['tmp_name'];

    $imageData = file_get_contents($imagePath);
    $base64Image = base64_encode($imageData);

    $requestBody = json_encode([
        "contents" => [
            [
                "parts" => [
                    [
                        "inline_data" => [
                            "mime_type" =>"image/jpeg",
                            "data" => $base64Image
                        ]
                        ],
                        [
                            "text" => "Analisis gambar ini dan berikan deskripsi dengan panjang maksimal 500 karakter serta skor seberapa 'skena-nya' maksud dari 'skena' adalah istilah yang dipakai oleh anak muda Indonesia untuk menyebut style pakaian atau outfit yang menarik dan mewah. Tidak hanya mewah, namun juga terkesan elegan dan tidak norak dipakainya. Penilaian di nilai dari style atau outfit yang dipakainya mulai dari 0-100."
                        ]
                ]
            ]

        ]
    ]);

    $ch = curl_init("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=$apikey");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    $textDescription = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Deskripsi tidak ditemukan";

    preg_match('/skor[^0-9]{0,10}(\d{1,3})/i', $textDescription, $matches);

    $skenaScore = isset($matches[1]) ? $matches[1] : "Tidak ada skor ditemukan";




    echo json_encode([
        "description" => $textDescription,
        "skena_score" => $skenaScore
    ], JSON_PRETTY_PRINT);

} else {
    echo json_encode(["error" => "Gagal mengunggah gambar"]);
}
