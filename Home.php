<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toll Tax Management System</title>
    <style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background-color: #e0f7fa; 
    color: #004d40;
}

nav {
    background-color: #004d80;
    color: white;
    padding: 15px;
    text-align: center;
}
nav a {
    color: white;
    margin: 0 15px;
    text-decoration: none;
    font-weight: 500;
}
nav a:hover {
    text-decoration: underline;
}

.section {
    padding: 40px 80px;
    text-align: justify;
}

h2 {
    color: #00334d;
    text-align: center;
}

table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #006064;
    padding: 10px;
    text-align: center;
}
th {
    background-color: #007b9e;
    color: white;
}

.btn {
    background-color: #0097a7;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}
.btn:hover {
    background-color: #006f81;
}

footer {
    background-color: #004d80; 
    color: white;
    text-align: center;
    padding: 15px 0;
    margin-top: 50px;
    font-size: 15px;
}
form {
    width: 60%;
    margin: 0 auto;
}
label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #004d40;
}
input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #0097a7;
    border-radius: 6px;
    margin-bottom: 15px;
    font-size: 15px;
    background-color: #f0fbfc;
}
    </style>
</head>
<body>
    <nav>
        <a href="#about">About</a>
        <a href="#rates">Toll Rates</a>
        <a href="#announcements">Announcements</a>
        <a href="#contact_us">Contact Us</a>
        <a href="Login.php">Login</a>
        <a href="register.php">Create Account</a>
    </nav>
        <h1 style="text-align:center; color:#00334d;">Welcome to Toll Tax Management System</h1>
    <section id="about" class="section">
        <h2>About Toll Tax System</h2>
        <p> The Toll Tax System is a vital part of modern road infrastructure that ensures proper maintenance and
            development of highways and expressways. The concept of toll collection has existed for centuries — 
            dating back to ancient times when travelers paid for the upkeep of roads, bridges, and city gates.
        </p>
        <p>
            In India, toll collection gained prominence with the rise of the National Highways Authority of India (NHAI), 
            which introduced structured toll plazas to finance the construction and maintenance of expressways. 
            Over time, the process evolved from manual receipts to smart, electronic tolling systems using RFID and FASTag.
            Today, toll taxes not only fund infrastructure but also reduce congestion and travel time through digital payments. 
            Our Toll Tax Management System simplifies this process by offering an online portal where users can view toll 
            rates, get updates, make digital payments, and raise queries — all in one place.
        </p>
        <p>
            These funds are vital for repairing damaged roads, expanding lanes, and ensuring safety. With the evolution of digital payment 
            technologies like FASTag, toll collection has become quicker, cashless, and more transparent — reducing waiting time, saving fuel,
            and promoting eco-friendly transport.
            Our Toll Tax Management System brings all toll services to one place — from viewing toll rates to making payments, tracking updates,
            and contacting support — everything is now simple, secure, and efficient.
        </p>
    </section>
    <section id="rates" class="section">
        <h2>Toll Rates Information</h2>
        <table>
            <tr><th>Vehicle Type</th><th>Single Journey</th><th>Return Journey</th></tr>
            <tr><td>Car / Jeep / Van</td><td>₹70</td><td>₹100</td></tr>
            <tr><td>Bus / Truck</td><td>₹230</td><td>₹345</td></tr>
            <tr><td>Heavy Vehicle (3 Axle)</td><td>₹400</td><td>₹600</td></tr>
            <tr><td>Multi Axle Vehicle (4–6 Axle)</td><td>₹550</td><td>₹825</td></tr>
            <tr><td>Oversized Vehicle</td><td>₹700</td><td>₹1050</td></tr>
        </table>
    </section>
    <section id="announcements" class="section">
        <h2>Announcements & Updates</h2>
        <ul>
            <li><b>Policy Update:</b> FASTag mandatory from January 2025.</li>
            <li><b>New Rates:</b> Revised toll charges from March 2025.</li>
            <li><b>Maintenance Notice:</b> NH-44 Toll Plaza closed on Nov 3 for maintenance.</li>
        </ul>
    </section>
    <section id="contact_us" class="section">
        <h2>Contact Us</h2>
        <form action="contact_us.php" method="POST">
            <label>Name:</label>
            <input type="text" name="name" placeholder="Enter your name" required>
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            <label>Message:</label>
            <textarea name="message" rows="4" placeholder="Enter your message" required></textarea>
            <button type="submit" class="btn">Send Message</button>
        </form>
    </section>
    <footer>
        &copy; 2025 Toll Tax Management System | Designed for Smart Roadways
    </footer>
</body>
</html>
