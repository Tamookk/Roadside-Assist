<?php


class Report
{
    private $id;
    private $type;
    private $date;

    function __construct($id, $type, $date)
    {
        /*
         * Constructor.
         */
        // Set class variables
        $this->id = $id;
        $this->type = $type;
        $this->date = $date;
    }

    public static function getUserRating($username)
    {
        /*
         * Return the average rating for user specified by 'username'.
         */
        // Variables to store rating and amount of ratings
        $rating = 0;
        $num_ratings = 0;

        // Connect to review database
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/review.db" : "webserver/res/review.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Prepare and execute statement
        $stmt = $pdo->prepare('SELECT Rating FROM Review WHERE ReviewTo = :username;');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch data
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, user has no reviews. Return 0
        if (count($data) < 1)
            return 0;

        // Loop through data and find weighted rating
        foreach ($data as $row)
        {
            $rating += $row['Rating'];
            $num_ratings ++;
        }

        // Return average rating
        return round($rating / $num_ratings, 2);
    }

    public static function generateReviewReport($username)
    {
        /*
         * Generate a review report for the user specified by 'username'.
         */
        // Connect to review database
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/review.db" : "webserver/res/review.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Prepare and execute statement
        $stmt = $pdo->prepare('SELECT * FROM Review WHERE ReviewTo = :username;');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch data
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, user has no reviews. Return 0
        if (count($data) < 1)
            return 0;

        // Loop through data, add review data to 2D array to return
        $review_data = [];

        // Push data into array
        foreach ($data as $row)
            $review_data[] = [$row['Date'], $row['Rating'], $row['Review']];

        // Return review data
        return $review_data;
    }

    public static function generateRepairReport($username)
    {
        /*
         * Generate a repair report for the user specified by 'username'.
         */
        // Connect to waitlist database
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Prepare and execute statement
        $stmt = $pdo->prepare('SELECT * FROM Closed WHERE email = :username;');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch data
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, user has no repairs. Return 0
        if (count($data) < 1)
            return 0;

        // Loop through data, add review data to 2D array to return
        $review_data = [];

        // Push data into array
        foreach ($data as $row)
            $review_data[] = [date('Y-m-d', $row['timestamp']), $row['company'], $row['Lat'] . ',' . $row['Lon'], $row['vehicleDetails']];

        // Return review data
        return $review_data;
    }

    public static function generatePaymentReport($username)
    {
        /*
         * Generate a payment report for the user specified by 'username'.
         */
        // Connect to waitlist database
        $db_location = str_ends_with(getcwd(), 'www') ? "../res/waitlist.db" : "webserver/res/waitlist.db";
        $pdo = new \PDO("sqlite:" . $db_location);

        // Prepare and execute statement
        $stmt = $pdo->prepare('SELECT * FROM Closed WHERE email = :username;');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch data
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        // If length is less than 1, user has no payments. Return 0
        if (count($data) < 1)
            return 0;

        // Loop through data, add review data to 2D array to return
        $review_data = [];

        // Push data into array
        foreach ($data as $row)
            $review_data[] = [date('Y-m-d', $row['timestamp']), $row['company'], $row['cost']];

        // Return review data
        return $review_data;
    }

    public function getReportID()
    {
        /*
         * Returns the report ID.
         */
        return $this->id;
    }

    public function getReportType()
    {
        /*
         * Returns the report type.
         */
        return $this->type;
    }

    public function getReportDate()
    {
        /*
         * Returns the report date.
         */
        return $this->date;
    }
}