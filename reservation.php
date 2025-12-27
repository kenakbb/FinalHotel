<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hotel Booking</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: linear-gradient(to right, #141e30, #243b55);
    color: #fff;
}

.container {
    width: 90%;
    max-width: 1100px;
    margin: auto;
}

header img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

nav {
    background: #111;
    padding: 10px 0;
}

nav ul {
    list-style: none;
    display: flex;
    justify-content: space-between;
    margin: 0;
    padding: 0 20px;
}

nav ul li a {
    color: #f4b41a;
    text-decoration: none;
    padding: 8px 15px;
}

nav ul li a:hover {
    background: #f4b41a;
    color: #000;
    border-radius: 4px;
}

.card {
    background: rgba(0,0,0,0.75);
    padding: 20px;
    border-radius: 8px;
    margin: 30px auto;
    max-width: 450px;
}

.card h3 {
    text-align: center;
    margin-bottom: 20px;
    color: #f4b41a;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
}

input[type="date"] {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: none;
}

button {
    width: 100%;
    padding: 12px;
    background: #f4b41a;
    border: none;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
}

button:hover {
    background: #ffcc33;
}

.room-card {
    background: rgba(0,0,0,0.75);
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.room-card h4 {
    color: #f4b41a;
}

.book-btn {
    margin-top: 10px;
    display: inline-block;
    background: #f4b41a;
    color: #000;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 4px;
}
</style>
</head>

<body>

<header class="container">
    <img src="images/home_banner.jpg" alt="Hotel Banner">
</header>

<nav>
    <ul>
        <li>
            <a href="index.php">Home</a>
            <a href="room.php">Rooms</a>
            <a href="reservation.php">Reservation</a>
            <a href="admin.php">Admin</a>
        </li>
    </ul>
</nav>

<div class="container">

<div class="card">
    <h3>Check Room Availability</h3>
    <form method="post">
        <div class="form-group">
            <label>Check In</label>
            <input type="date" name="checkin" required>
        </div>

        <div class="form-group">
            <label>Check Out</label>
            <input type="date" name="checkout" required>
        </div>

        <button type="submit" name="submit">Check Availability</button>
    </form>
</div>

<?php
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $room_cat = $row['room_cat'];
        $sql = "SELECT * FROM room_category WHERE roomname='$room_cat'";
        $query = mysqli_query($user->db, $sql);
        $room = mysqli_fetch_assoc($query);
?>
    <div class="room-card">
        <h4><?= $room['roomname']; ?></h4>
        <p>Beds: <?= $room['no_bed']." ".$room['bedtype']; ?></p>
        <p>Available: <?= $room['available']; ?></p>
        <p>Facilities: <?= $room['facility']; ?></p>
        <p>Price: <?= $room['price']; ?> / night</p>

        <a class="book-btn" href="booknow.php?roomname=<?= $room['roomname']; ?>">Book Now</a>
    </div>
<?php
    }
}
?>

</div>

</body>
</html>
