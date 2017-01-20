# SudokuChecker

## Requirements
Vagrant version 1.8.1
Phpunit version 5.1.3

## Setup
1. Clone the repository `git clone git@github.com:karazeyandrey/SudokuChecker.git`
2. Run the vagrant script `vagrant up`

## Functionality
1. Sudoku move check can be done with GET 'http://localhost:8080/sudoku/{0-8}/{0-8}/{1-9}/'
    Where first parameter - sudoku row, second - sudoku column, third - checked value.
2. Current sudoku state can be received with GET 'http://localhost:8080/sudoku/current/'
3. Sudoku state can be reseted with GET 'http://localhost:8080/sudoku/reset/'
4. Tests can be runned inside symfony directory using command 'phpunit'
