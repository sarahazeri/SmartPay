<?php

namespace database\migrations;

class CreateTransactionsTable
{
    public static function up()
    {
        $query = "
            CREATE TABLE transactions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id VARCHAR(50) NOT NULL UNIQUE,
                amount DECIMAL(10, 3) NOT NULL,
                currency CHAR(3) NOT NULL DEFAULT 'OMR',
                status VARCHAR(20) NOT NULL DEFAULT 'INITIATED',
                tracking_id VARCHAR(100),
                response TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            );
        ";

        // اجرای کوئری در پایگاه داده
        $db = new \PDO('mysql:host=127.0.0.1;dbname=your_db', 'username', 'password');
        $db->exec($query);
    }
}
