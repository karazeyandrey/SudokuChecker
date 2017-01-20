<?php

namespace SudokuBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * SudokuControllerTest Class Doc Comment
 *
 * @category Class
 */
class SudokuControllerTest extends WebTestCase
{
    /**
     * Test check action
     */
    public function testCheck()
    {
        $client = static::createClient();

        // proper data
        $crawler = $client->request('GET', '/sudoku/check/8/8/7/');

        $response = json_decode($client->getResponse()->getContent());
        var_dump($client->getResponse()->getStatusCode());

        // check status
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // check format
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        // check value valid
        $this->assertEquals(true, $response->data->valid);

        // check sudoku not solved
        $this->assertEquals(false, $response->data->solved);

        // check wrong params
        $crawler = $client->request('GET', '/sudoku/check/8/8/10/');

        // check error status
        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $client->getResponse()->getStatusCode()
        );

        // check error message
        $response = json_decode($client->getResponse()->getContent());

        $this->assertEquals(
            'Missing parameters',
            $response->errorMessage
        );
    }

    /**
     * Test reset action
     */
    public function testReset()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sudoku/reset/');

        $response = json_decode($client->getResponse()->getContent());

        // check status
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // check format
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        // check success
        $this->assertEquals('true', $response->success);
    }

    /**
     * Test getCurrentSudoku action
     */
    public function testGetCurrentSudoku()
    {
        $client = static::createClient();
        $startSudoku = [
            [7, 0, 0, 0, 4, 0, 5, 3, 0],
            [0, 0, 5, 0, 0, 8, 0, 1, 0],
            [0, 0, 8, 5, 0, 9, 0, 4, 0],
            [5, 3, 9, 0, 6, 0, 0, 0, 1],
            [0, 0, 0, 0, 1, 0, 0, 0, 5],
            [8, 0, 0, 7, 2, 0, 9, 0, 0],
            [9, 0, 7, 4, 0, 0, 0, 0, 0],
            [0, 0, 0, 0, 5, 7, 0, 0, 0],
            [6, 0, 0, 0, 0, 0, 0, 5, 0],
        ];

        $crawler = $client->request('GET', '/sudoku/current/');

        $response = json_decode($client->getResponse()->getContent());

        // check status
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // check format
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );

        // check success
        $this->assertEquals('true', $response->success);

        // check data
        $this->assertEquals($startSudoku, $response->data);
    }
}
