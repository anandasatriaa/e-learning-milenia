<?php

namespace App\Imports;

use App\Models\Course\ModulQuiz;
use App\Models\Course\ModulQuizAnswer;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class QuizModulImport implements ToArray, WithStartRow, WithCalculatedFormulas
{
    use Importable;

    protected $startRow = 18;
    protected $course_modul_id;
    protected $resultError = [];

    public function __construct(string $course_modul_id)
    {
        $this->course_modul_id = $course_modul_id;
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    public function array(array $row)
    {
        try {
            DB::beginTransaction();
            ModulQuiz::where('course_modul_id', $this->course_modul_id)->delete();

            foreach ($row as $key => $item) {
                $quiz = new ModulQuiz();
                $quiz->course_modul_id = $this->course_modul_id;
                $quiz->pertanyaan = $item[1];
                $quiz->kunci_jawaban = $item[7];

                $quiz->save();

                for ($i = 2; $i <= 6; $i++) {
                    $answer = new ModulQuizAnswer();
                    $answer->modul_quiz_id = $quiz->id;
                    $answer->pilihan = $item[$i];
                    $answer->save();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            $this->resultError[] = $th->getMessage();
            DB::rollBack();
        }
    }

    public function getErrorImport()
    {
        return $this->resultError;
    }
}
