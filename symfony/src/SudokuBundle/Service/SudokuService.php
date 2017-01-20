<?php

namespace SudokuBundle\Service;

use Symfony\Component\DependencyInjection\Container;

/**
 * SudokuService Class Doc Comment
 *
 * @category Class
 * @package  SudokuBundle
 */
class SudokuService
{
    /**
     * To store start sudoku state
     * @var array
     */
    private $startSudoku = [
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

    /**
     * To store session
     * @var object
     */
    private $session;


    /**
     * SudokuService constructor
     *
     * @param object $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->session = $container->get('session');
        $this->initCurrentSudoku();
    }

    /**
     * Check move method
     *
     * @param int $row
     * @param int $col
     * @param int $value
     * @return boolean
     */
    public function checkMove($row, $col, $value)
    {
        $valid = true;
        $currentSudoku = $this->getCurrentSudoku();

        // check if value not set
        if (!$currentSudoku[$row][$col]) {
            // check row & col values
            for ($i = 0; $i < 9; $i++) {
                if ($currentSudoku[$row][$i] === $value ||
                    $currentSudoku[$i][$col] === $value) {
                    $valid = false;
                }
            }

            $valid = $this->checkSmallSquare($row, $col, $value, $valid);

            if ($valid) {
                $currentSudoku[$row][$col] = $value;
                $this->setCurrentSudoku($currentSudoku);
            }
        } else {
            $valid = false;
        }

        return $valid;
    }


    /**
     * Check current sudoku solved
     *
     * @return boolean
     */
    public function checkSolved()
    {
        $currentSudoku = $this->getCurrentSudoku();
        $solved = true;

        foreach ($currentSudoku as $arr) {
            if (array_search(0, $arr) !== false) {
                $solved = false;
            }
        }

        return $solved;
    }

    /**
     * Reset current sudoku
     *
     * @return array
     */
    public function resetCurrentSudoku()
    {
        $this->session->set('currentSudoku', $this->startSudoku);
    }

    /**
     * Check current sudoku solved
     *
     * @return array
     */
    public function getCurrentSudoku()
    {
        return $this->session->get('currentSudoku');
    }

    /**
     * Check current sudoku solved
     *
     * @param array $sudoku
     * @return void
     */
    private function setCurrentSudoku($sudoku)
    {
        $this->session->set('currentSudoku', $sudoku);
    }

    /**
     * Init current sudoku in session
     *
     * @return void
     */
    private function initCurrentSudoku()
    {
        if (is_null($this->session->get('currentSudoku'))) {
            $this->resetCurrentSudoku();
        }
    }

    /**
     * Check small square values
     *
     * @param int $row
     * @param int $col
     * @param int $value
     * @param bool $valid
     * @return boolean
     */
    private function checkSmallSquare($row, $col, $value, $valid)
    {
        $currentSudoku = $this->getCurrentSudoku();
        $rowStart = $this->findGridStart($row);
        $colStart = $this->findGridStart($col);

        for ($i = $rowStart; $i < $rowStart + 3; $i++) {
            for ($j = $colStart; $j < $colStart + 3; $j++) {
                if ($currentSudoku[$i][$j] === $value) {
                    $valid = false;
                }
            }
        }

        return $valid;
    }

    /**
     * Get the start of the current 3x3 grid
     *
     * @param int $value
     * @return int
     */
    private function findGridStart($value)
    {
        return floor($value / 3) * 3;
    }
}
