<?php

namespace SudokuBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * SudokuController Class Doc Comment
 *
 * @category Class
 * @package  SudokuBundle
 */
class SudokuController extends FOSRestController
{
    /**
     * @Rest\Get("/sudoku/check/{row}/{col}/{value}/")
     * @param string $row
     * @param string $col
     * @param string $value
     * @return JsonResponse
     */
    public function checkAction($row, $col, $value)
    {
        $sudokuService = $this->get('app.sudoku_service');

        // sanitize params
        $row = intval($row, 10);
        $col = intval($col, 10);
        $value = intval($value, 10);
        $validRange = range(0, 8);

        // check params not out of range
        if (!in_array($row, $validRange) ||
            !in_array($col, $validRange) ||
            !in_array($value, range(1, 9))) {
            return new JsonResponse(['errorMessage' => 'Missing parameters'], 400);
        }

        return new JsonResponse(
            [
                'success' => 'true',
                'data' => [
                    'valid' => $sudokuService->checkMove($row, $col, $value),
                    'solved' => $sudokuService->checkSolved(),
                ],
            ],
            200
        );
    }

    /**
     * @Rest\Get("/sudoku/reset/")
     * @return JsonResponse
     */
    public function resetAction()
    {
        $sudokuService = $this->get('app.sudoku_service');
        $sudokuService->resetCurrentSudoku();

        return new JsonResponse(['success' => 'true'], 200);
    }

    /**
     * @Rest\Get("/sudoku/current/")
     * @return JsonResponse
     */
    public function getCurrentSudokuAction()
    {
        $sudokuService = $this->get('app.sudoku_service');

        return new JsonResponse(
            [
                'success' => 'true',
                'data' => $sudokuService->getCurrentSudoku(),
            ],
            200
        );
    }
}
