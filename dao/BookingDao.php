<?php
/**
 * Description of BookingDao
 *
 * @author richard_lovell
 */
class BookingDao {
    
    /** @var PDO */
    private $db = null;
    public function __destruct() {
        // close db connection
        $this->db = null;
    }
    /**
     * Find all {@link Booking}s by search criteria.
     * @return array array of {@link Booking}s
     */
    public function find($sql) {
        $result = array();
        foreach ($this->query($sql) as $row) {
            $booking = new Booking();
            BookingMapper::map($booking, $row);
            $result[$booking->getId()] = $booking;
        }
        return $result;
    }
    /**
     * Find {@link Booking} by identifier.
     * @return Booking Booking or <i>null</i> if not found
     */
    public function findById($id) {
        $row = $this->query('SELECT * FROM bookings WHERE status != "deleted" AND id = ' . (int) $id)->fetch();
        if (!$row) {
            return null;
        }
        $booking = new Booking;
        BookingMapper::map($booking, $row);
        return $booking;
    }
    /**
     * Save {@link Booking}.
     * @param ToDo $booking {@link Booking} to be saved
     * @return Booking saved {@link Booking} instance
     */
    public function save(Booking $booking) {
        if ($booking->getId() === null) {
            return $this->insert($booking);
        }
        return $this->update($booking);
    }
    /**
     * Delete {@link Booking} by identifier.
     * @param int $id {@link Booking} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            UPDATE bookings SET
                status = :status
            WHERE
                id = :id';
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, array(
            ':status' => 'deleted',
            ':id' => $id,
        ));
        return $statement->rowCount() == 1;
    }
    /**
     * @return PDO
     */
    private function getDb() {
        if ($this->db !== null) {
            return $this->db;
        }
        $config = Config::getConfig("db");
        try {
            $this->db = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (Exception $ex) {
            throw new Exception('DB connection error: ' . $ex->getMessage());
        }
        return $this->db;
    }
//    private function getFindSql(BookingSearchCriteria $search = null) {
//        $sql = 'SELECT * FROM todo WHERE deleted = 0 ';
//        $orderBy = ' priority, due_on';
//        if ($search !== null) {
//            if ($search->getStatus() !== null) {
//                $sql .= 'AND status = ' . $this->getDb()->quote($search->getStatus());
//                switch ($search->getStatus()) {
//                    case Booking::STATUS_PENDING:
//                        $orderBy = 'due_on, priority';
//                        break;
//                    case Booking::STATUS_DONE:
//                    case Booking::STATUS_VOIDED:
//                        $orderBy = 'due_on DESC, priority';
//                        break;
//                    default:
//                        throw new Exception('No order for status: ' . $search->getStatus());
//                }
//            }
//        }
//        $sql .= ' ORDER BY ' . $orderBy;
//        return $sql;
//    }
    /**
     * @return Booking
     * @throws Exception
     */
    private function insert(Booking $booking) {
        $now = new DateTime();
        $booking->setId(null);
        $booking->setStatus('pending');
        $sql = '
            INSERT INTO bookings (id, flight_name, flight_date, status, user_id)
                VALUES (:id :flight_name :flight_date :status :user_id)';
        return $this->execute($sql, $booking);
    }
    /**
     * @return Booking
     * @throws Exception
     */
//    private function update(Booking $todo) {
//        $todo->setLastModifiedOn(new DateTime());
//        $sql = '
//            UPDATE todo SET
//                priority = :priority,
//                last_modified_on = :last_modified_on,
//                due_on = :due_on,
//                title = :title,
//                description = :description,
//                comment = :comment,
//                status = :status,
//                deleted = :deleted
//            WHERE
//                id = :id';
//        return $this->execute($sql, $todo);
//    }
    /**
     * @return Booking
     * @throws Exception
     */
    private function execute($sql, Booking $booking) {
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, $this->getParams($booking));
        if (!$booking->getId()) {
            return $this->findById($this->getDb()->lastInsertId());
        }
        if (!$statement->rowCount()) {
            throw new NotFoundException('Booking with ID "' . $booking->getId() . '" does not exist.');
        }
        return $booking;
    }
    private function getParams(Booking $booking) {
        $params = array(
            ':id' => $booking->getId(),
            ':flight_name' => $booking->getFlightName(),
            ':flight_date' => self::formatDateTime($booking->getFlightDate()),
            ':status' => $booking->getStatus(),
            ':user_id' => $booking->getUserId()  
        );
        return $params;
    }
    private function executeStatement(PDOStatement $statement, array $params) {
        if (!$statement->execute($params)) {
            self::throwDbError($this->getDb()->errorInfo());
        }
    }
    /**
     * @return PDOStatement
     */
    private function query($sql) {
        $statement = $this->getDb()->query($sql, PDO::FETCH_ASSOC);
        if ($statement === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
        return $statement;
    }
    private static function throwDbError(array $errorInfo) {
        // TODO log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }
    private static function formatDateTime(DateTime $date) {
        return $date->format(DateTime::ISO8601);
    }
}