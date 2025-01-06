<?php

namespace App\Imports;

use App\Models\Course\ModulQuiz;
use App\Models\Course\ModulQuizAnswer;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Log;

class QuizModulImport implements ToArray, WithStartRow, WithCalculatedFormulas
{
    use Importable;

    protected $startRow = 18;
    protected $course_modul_id;
    protected $resultError = [];
    protected $drawings = []; // Array untuk menyimpan gambar dari Excel

    public function __construct(string $course_modul_id)
    {
        $this->course_modul_id = $course_modul_id;
    }

    public function startRow(): int
    {
        return $this->startRow;
    }

    /**
     * Memuat gambar dari file Excel
     */
    private function loadDrawings($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();

        foreach ($worksheet->getDrawingCollection() as $drawing) {
            if ($drawing instanceof Drawing) {
                Log::info("Found drawing: " . $drawing->getCoordinates());
                $coordinates = $drawing->getCoordinates(); // Koordinat sel tempat gambar berada
                $imagePath = $drawing->getPath(); // Mendapatkan path gambar
                Log::info("Image path: {$imagePath}");
                
                $imageResource = imagecreatefromstring(file_get_contents($imagePath)); // Mengambil gambar dari file
                if ($imageResource) {
                    Log::info("Image successfully loaded from: {$imagePath}");
                } else {
                    Log::error("Failed to load image from: {$imagePath}");
                }

                if ($imageResource) {
                    ob_start();
                    imagepng($imageResource);
                    $imageData = ob_get_contents();
                    ob_end_clean();

                    // Pastikan gambar ada dalam $imageData
                    if (!empty($imageData)) {
                        Log::info("Image for coordinates {$coordinates} is ready for saving.");
                        $this->drawings[$coordinates] = $imageData;
                    } else {
                        Log::error("No image data for coordinates {$coordinates}.");
                    }
                } else {
                    Log::error("Failed to load image for coordinates {$coordinates}.");
                }
            } else {
                Log::error("Non-drawing object found at coordinates: " . $drawing->getCoordinates());
            }
        }
    }

    public function array(array $row)
    {
        try {
            DB::beginTransaction();

            // Hapus data quiz yang terkait dengan modul
            ModulQuiz::where('course_modul_id', $this->course_modul_id)->delete();

            // Muat gambar dari file Excel
            $filePath = request()->file('excel')->getPathName(); // Path file yang diupload
            $this->loadDrawings($filePath);

            if (request()->hasFile('excel')) {
                Log::info('File uploaded: ' . request()->file('excel')->getClientOriginalName());
            } else {
                Log::error('No file uploaded.');
            }

            foreach ($row as $key => $item) {
                try {
                    // Buat instance quiz baru
                    $quiz = new ModulQuiz();
                    $quiz->course_modul_id = $this->course_modul_id;
                    $quiz->pertanyaan = $item[2] ?? null; // Pastikan kolom tersedia
                    $quiz->kunci_jawaban = $item[7] ?? null;

                    Log::info("Item data: " . json_encode($item));

                    // Proses gambar berdasarkan koordinat sel
                    $cellCoordinates = 'B' . ($this->startRow + $key); // Contoh: B18, B19, dst.
                    Log::info("Processing cell pertanyaan: {$cellCoordinates}");
                    if (array_key_exists($cellCoordinates, $this->drawings)) {
                        Log::info("Found image for coordinates {$cellCoordinates}");
                        try {
                            $imageName = uniqid() . '.png';
                            $path = "public/quiz/image/{$imageName}";
                            Storage::put($path, $this->drawings[$cellCoordinates]);

                            if (Storage::exists($path)) {
                                Log::info("Image successfully saved: {$path}");
                                $quiz->image = "storage/quiz/image/{$imageName}";
                            } else {
                                Log::error("Failed to save image at: {$path}");
                            }
                        } catch (\Exception $e) {
                            Log::error("Failed to process image for quiz (Row $key): " . $e->getMessage());
                            $this->resultError[] = "Image processing error for row $key: " . $e->getMessage();
                        }
                    }

                    // Simpan quiz
                    $quiz->save();

                    // Proses jawaban
                    $columns = ['D', 'E', 'F', 'G']; // Kolom D hingga G
                    $columnsIndex = ['3', '4', '5', '6'];

                    foreach ($columns as $index => $column) {
                        $cellCoordinates = $column . ($this->startRow + $key); // D18, E18, dst.
                        $columnIndex = $columnsIndex[$index]; // Ambil indeks numerik yang sesuai
                        $text = $item[$columnIndex] ?? null; // Ambil nilai berdasarkan indeks

                        Log::info("Item data column {$column} (Index {$columnIndex}): " . json_encode($text));
                        Log::info("Processing cell {$cellCoordinates}, raw value: " . json_encode($text));

                        $answer = new ModulQuizAnswer();
                        $answer->modul_quiz_id = $quiz->id;

                        if (!empty($text)) {
                            $answer->pilihan = $text;
                        } else {
                            $answer->pilihan = 'N/A';
                        }

                        // Periksa apakah ada gambar pada cell ini
                        if (array_key_exists($cellCoordinates, $this->drawings)) {
                            // Jika gambar ada, simpan gambar
                            try {
                                $imageName = uniqid() . '.png';
                                $path = "public/quiz/answers/{$imageName}";
                                Storage::put($path, $this->drawings[$cellCoordinates]);

                                if (Storage::exists($path)) {
                                    Log::info("Answer image successfully saved: {$path}");
                                    $answer->pilihan = "storage/quiz/answers/{$imageName}";
                                } else {
                                    Log::error("Failed to save answer image at: {$path}");
                                }
                            } catch (\Exception $e) {
                                Log::error("Failed to process answer image (Row $key, Col $column): " . $e->getMessage());
                                $this->resultError[] = "Answer image processing error for row $key, col $column: " . $e->getMessage();
                            }
                        }

                        // Simpan jawaban
                        $answer->save();
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to process quiz for row $key: " . $e->getMessage());
                    $this->resultError[] = "Quiz processing error for row $key: " . $e->getMessage();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            $this->resultError[] = $th->getMessage();
            Log::error("Transaction failed: " . $th->getMessage());
            DB::rollBack();
        }
    }

    public function getErrorImport()
    {
        return $this->resultError;
    }
}
