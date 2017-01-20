<?php

namespace SudokuBundle\Service;

use Symfony\Component\DependencyInjection\Container;

class SudokuService
{
	/**
	 * To store start sudoku state
	 * @access private
	 * @var array
	 */
	private $_startSudoku = [
		[7,0,0,0,4,0,5,3,0],
		[0,0,5,0,0,8,0,1,0],
		[0,0,8,5,0,9,0,4,0],
		[5,3,9,0,6,0,0,0,1],
		[0,0,0,0,1,0,0,0,5],
		[8,0,0,7,2,0,9,0,0],
		[9,0,7,4,0,0,0,0,0],
		[0,0,0,0,5,7,0,0,0],
		[6,0,0,0,0,0,0,5,0]
	];

	/**
	 * To store session
	 * @access private
	 * @var object
	 */
	private $_session;


	/**
	 * SudokuService constructor
	 *
	 * @access public
	 * @param object $container
	 * @return void
	 */
	public function __construct(Container $container)
	{
		$this->_session = $container->get('session');
		$this->_initCurrentSudoku();
	}

	/**
	 * Check move method
	 *
	 * @access public
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
				if (
					$currentSudoku[$row][$i] === $value ||
					$currentSudoku[$i][$col] === $value
				) {
					$valid = false;
				}
			}

			$valid = $this->_checkSmallSquare($row, $col, $value, $valid);

			if ($valid) {
				$currentSudoku[$row][$col] = $value;
				$this->_setCurrentSudoku($currentSudoku);
			}
		} else {
			$valid = false;
		}

		return $valid;
	}


	/**
	 * Check current sudoku solved
	 *
	 * @access public
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
	 * @access public
	 * @return array
	 */
	public function resetCurrentSudoku()
	{
		$this->_session->set('currentSudoku', $this->_startSudoku);
	}

	/**
	 * Check current sudoku solved
	 *
	 * @access public
	 * @return array
	 */
	public function getCurrentSudoku()
	{
		return $this->_session->get('currentSudoku');
	}

	/**
	 * Check current sudoku solved
	 *
	 * @access private
	 * @param array $sudoku
	 * @return void
	 */
	private function _setCurrentSudoku($sudoku)
	{
		$this->_session->set('currentSudoku', $sudoku);
	}

	/**
	 * Init current sudoku in session
	 *
	 * @access private
	 * @return void
	 */
	private function _initCurrentSudoku()
	{
		if (is_null($this->_session->get('currentSudoku')))	{
			$this->resetCurrentSudoku();
		}
	}

	/**
	 * Check small square values
	 *
	 * @access private
	 * @param int $row
	 * @param int $col
	 * @param int $value
	 * @param bool $valid
	 * @return boolean
	 */
	private function _checkSmallSquare($row, $col, $value, $valid)
	{
		$currentSudoku = $this->getCurrentSudoku();
		$rowStart = $this->_findGridStart($row);
		$colStart = $this->_findGridStart($col);

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
	 * @access private
	 * @param int $value
	 * @return int
	 */
	private function _findGridStart($value)
	{
		return floor($value / 3) * 3;
	}
}
