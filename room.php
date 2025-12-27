<?php
session_start();
$rooms_json = file_exists('data/rooms.json') ? 
              json_decode(file_get_contents('data/rooms.json'), true) : [];
$categories_json = file_exists('data/categories.json') ? 
                   json_decode(file_get_contents('data/categories.json'), true) : [];

if (empty($categories_json)) {
    $categories_json = [
        [
            'roomname' => 'Family',
            'room_qnty' => 5,
            'available' => 5,
            'booked' => 0,
            'no_bed' => 2,
            'bedtype' => 'double',
            'facility' => 'Sofa, TV, WIFI, Balcony, AC.',
            'price' => 3500
        ],
        [
            'roomname' => 'Super Comfort',
            'room_qnty' => 5,
            'available' => 5,
            'booked' => 0,
            'no_bed' => 1,
            'bedtype' => 'double',
            'facility' => 'AC, TV, WIFI',
            'price' => 2200
        ],
        [
            'roomname' => 'Duplex',
            'room_qnty' => 5,
            'available' => 5,
            'booked' => 0,
            'no_bed' => 2,
            'bedtype' => 'single',
            'facility' => 'AC, TV, Wifi',
            'price' => 1500
        ]
    ];

    file_put_contents('data/categories.json', json_encode($categories_json, JSON_PRETTY_PRINT));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Room & Facilities - Hotel Booking</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .rooms-container { max-width: 1000px; margin: 20px auto; }
        .room-category { background: rgba(0,0,0,0.7); padding: 25px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffbb2b; }
        .room-title { color: #ffbb2b; margin-bottom: 10px; font-size: 1.5em; }
        .btn-book { background: #ff9800; color: #333; padding: 12px 30px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="rooms-container">
            <h2 class="section-title">Room Categories & Facilities</h2>
            
            <?php foreach($categories_json as $category): ?>
            <div class="room-category">
                <h3 class="room-title"><?php echo $category['roomname']; ?></h3>
                <div style="color: #ffbb2b; font-size: 1.2em; margin: 10px 0;">
                    Price: ₹<?php echo $category['price']; ?> / night
                </div>
                <div style="color: #ffbb2b; margin: 5px 0;">
                    Beds: <?php echo $category['no_bed']; ?> <?php echo $category['bedtype']; ?> bed(s)
                </div>
                <div style="color: #ffbb2b; margin: 5px 0;">
                    Available: <?php echo $category['available']; ?> rooms
                </div>
                <div style="color: #fff; margin: 10px 0;">
                    Facilities: <?php echo $category['facility']; ?>
                </div>
                <a href="reservation.php?roomname=<?php echo urlencode($category['roomname']); ?>" class="btn-book">
                    Book Now
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Same footer as above -->
    </div>
</body>
</html>