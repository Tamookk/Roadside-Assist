<?php
use PHPUnit\Framework\TestCase;

class ReportTest extends TestCase
{
    // Report object variable
    private Report $report;

    public function setUp(): void
    {
        /*
         * Initialise the Report class with dummy data.
         */
        $this->report = new Report('1', 'Repair', '01/01/1970');
    }

    public function testGetUserRating()
    {
        /*
         * Tests that the user rating can be properly retrieved from the database.
         */
        // Assert a 0 is return (dummy username)
        $this->assertSame(Report::getUserRating('dummy'), 0);
    }

    public function testGenerateReviewReport()
    {
        /*
         * Tests that a review report can be generated correctly.
         */
        // Assert a 0 is returned (dummy username)
        $this->assertSame(Report::generateReviewReport('dummy'), 0);
    }

    public function testGenerateRepairReport()
    {
        /*
         * Tests that a repair report can be generated correctly.
         */
        // Assert a 0 is returned (dummy username)
        $this->assertSame(Report::generateRepairReport('dummy'), 0);
    }

    public function testGeneratePaymentReport()
    {
        /*
         * Tests that a payment report can be generated correctly.
         */
        // Assert a 0 is returned (dummy username)
        $this->assertSame(Report::generatePaymentReport('dummy'), 0);
    }

    public function testGetReportID()
    {
        /*
         * Tests that the Report ID variable is properly set and retrieved.
         */
        $this->assertSame('1', $this->report->getReportID());
    }

    public function testGetReportType()
    {
        /*
         * Tests that the Report type variable is properly set and retrieved.
         */
        $this->assertSame('Repair', $this->report->getReportType());
    }

    public function testGetReportDate()
    {
        /*
         * Tests that the Report date variable is properly set and retrieved.
         */
        $this->assertSame('01/01/1970', $this->report->getReportDate());
    }
}
