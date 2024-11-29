<?php
include "db_config.php";

class User
{
    public $db;

    public function __construct() {
        $this->db = new mysqli('localhost', 'root', '', 'hotel');
        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }
    }

    // Register a new user
    public function reg_user($name, $username, $password, $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM manager WHERE uname=? OR uemail=?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $stmt->close();

            // Insert new user
            $stmt = $this->db->prepare("INSERT INTO manager (uname, upass, fullname, uemail) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $name, $email);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            $stmt->close();
            return false;
        }
    }

    // Add a new room category
    public function add_room($roomname, $room_qnty, $no_bed, $bedtype, $facility, $price)
    {
        $available = $room_qnty;
        $booked = 0;

        // Insert room category
        $stmt = $this->db->prepare("INSERT INTO room_category (roomname, room_qnty, no_bed, bedtype, facility, price, available, booked) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissdii", $roomname, $room_qnty, $no_bed, $bedtype, $facility, $price, $available, $booked);

        if ($stmt->execute()) {
            for ($i = 0; $i < $room_qnty; $i++) {
                $stmt2 = $this->db->prepare("INSERT INTO rooms (room_cat, book) VALUES (?, 'false')");
                $stmt2->bind_param("s", $roomname);
                $stmt2->execute();
            }
            return true;
        } else {
            return false;
        }
    }

    // Check available rooms
    public function check_available($checkin, $checkout)
    {
        $sql = "SELECT DISTINCT room_cat 
                FROM rooms 
                WHERE room_id NOT IN (
                    SELECT DISTINCT room_id 
                    FROM rooms 
                    WHERE (checkin <= ? AND checkout >= ?) 
                       OR (checkin >= ? AND checkin <= ?) 
                       OR (checkin <= ? AND checkout >= ?)
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssss", $checkin, $checkout, $checkin, $checkout, $checkin, $checkout);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Book a room
    public function booknow($checkin, $checkout, $name, $phone, $roomname)
    {
        $sql = "SELECT * FROM rooms WHERE room_cat = ? AND book = 'false'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $roomname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['room_id'];

            // Update room booking
            $stmt2 = $this->db->prepare("UPDATE rooms SET checkin = ?, checkout = ?, name = ?, phone = ?, book = 'true' WHERE room_id = ?");
            $stmt2->bind_param("ssssi", $checkin, $checkout, $name, $phone, $id);

            if ($stmt2->execute()) {
                return "Your Room has been booked!";
            } else {
                return "Sorry, Internal Error";
            }
        } else {
            return "No Room Is Available";
        }
    }

    // Login check
    public function check_login($emailusername, $password)
    {
        $stmt = $this->db->prepare("SELECT uid FROM manager WHERE (uemail = ? OR uname = ?) AND upass = ?");
        $stmt->bind_param("sss", $emailusername, $emailusername, $password);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $_SESSION['login'] = true;
            $stmt->bind_result($uid);
            $stmt->fetch();
            $_SESSION['uid'] = $uid;
            return true;
        } else {
            return false;
        }
    }

    // Get session status
    public function get_session()
    {
        return $_SESSION['login'];
    }

    // Logout function
    public function user_logout()
    {
        $_SESSION['login'] = false;
        session_destroy();
    }
}
?>
