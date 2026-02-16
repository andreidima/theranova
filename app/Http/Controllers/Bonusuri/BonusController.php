<?php

namespace App\Http\Controllers\Bonusuri;

use App\Exports\BonusuriLunareExport;
use App\Http\Controllers\Controller;
use App\Models\FisaCaz;
use App\Models\Oferta;
use App\Models\User;
use App\Services\BonusCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BonusController extends Controller
{
    public function index(Request $request, BonusCalculatorService $bonusCalculatorService): View
    {
        $data = $this->collectRows($request, $bonusCalculatorService);
        $rows = $data['rows'];

        $sumar = [
            'total_bonus' => (int) $rows->sum('bonus_total'),
            'pozitii' => (int) $rows->count(),
            'fise_unice' => (int) $rows->pluck('fisa_caz_id')->unique()->count(),
        ];

        $perPage = 50;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $rowsPage = $rows->slice(($page - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $rowsPage,
            $rows->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('bonusuri.index', [
            'rows' => $paginator,
            'sumar' => $sumar,
            'month' => $data['month'],
            'canViewAll' => $data['canViewAll'],
            'users' => $data['canViewAll']
                ? User::query()->select('id', 'name')->where('activ', 1)->orderBy('name')->get()
                : collect(),
            'selectedUserId' => $data['selectedUserId'] > 0 ? $data['selectedUserId'] : null,
            'minUserTotal' => (int) $data['minUserTotal'],
        ]);
    }

    public function export(Request $request, BonusCalculatorService $bonusCalculatorService): StreamedResponse|BinaryFileResponse
    {
        $data = $this->collectRows($request, $bonusCalculatorService);
        $rows = $data['rows'];
        $month = $data['month'];
        $selectedUserId = $data['selectedUserId'];
        $minUserTotal = $data['minUserTotal'];

        $fileName = 'bonusuri-lunar-' . $month;
        if ($selectedUserId > 0) {
            $fileName .= '-user-' . $selectedUserId;
        }
        if ($minUserTotal > 0) {
            $fileName .= '-prag-' . $minUserTotal;
        }
        $fileName .= '.xlsx';

        return $this->downloadTemplateExport($rows, $month, $fileName);
    }

    public function situatii(Request $request): View
    {
        $canViewAll = $request->user()->hasRole('bonusuri.view_all');

        $query = FisaCaz::query()
            ->with([
                'pacient:id,nume,prenume,localitate,judet',
                'userVanzari:id,name',
                'userTehnic:id,name',
                'oferte' => function ($q) {
                    $q->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA)
                        ->orderBy('oferte.created_at')
                        ->orderBy('oferte.id');
                },
            ])
            ->whereHas('oferte', function ($q) {
                $q->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA);
            })
            ->where(function ($q) {
                $q->whereNull('protezare');
            });

        if (!$canViewAll) {
            $query->where(function ($q) use ($request) {
                $q->where('user_vanzari', $request->user()->id)
                    ->orWhere('user_tehnic', $request->user()->id);
            });
        }

        $fiseCaz = $query
            ->orderByDesc('id')
            ->paginate(100)
            ->withQueryString();

        return view('bonusuri.situatii', [
            'fiseCaz' => $fiseCaz,
            'canViewAll' => $canViewAll,
        ]);
    }

    protected function validatedMonth(?string $month): string
    {
        if (is_string($month) && preg_match('/^\d{4}\-\d{2}$/', $month)) {
            return $month;
        }

        return now()->format('Y-m');
    }

    protected function collectRows(Request $request, BonusCalculatorService $bonusCalculatorService): array
    {
        $canViewAll = $request->user()->hasRole('bonusuri.view_all');

        $month = $this->validatedMonth($request->input('month'));
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $selectedUserId = $canViewAll ? (int) $request->input('user_id', 0) : 0;
        $minUserTotal = $canViewAll ? max(0, (int) $request->input('min_user_total', 0)) : 0;

        $query = FisaCaz::query()
            ->with([
                'pacient:id,nume,prenume,localitate,judet',
                'userVanzari:id,name,email',
                'userTehnic:id,name,email',
                'lucrare:id,denumire,cod,activ',
                'latestDateMedicale' => function ($q) {
                    $q->select('date_medicale.id', 'date_medicale.fisa_caz_id', 'date_medicale.amputatie');
                },
                'oferte' => function ($q) {
                    $q->select('oferte.id', 'oferte.fisa_caz_id', 'oferte.pret', 'oferte.acceptata', 'oferte.created_at')
                        ->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA)
                        ->orderBy('oferte.created_at')
                        ->orderBy('oferte.id');
                },
            ])
            ->whereYear('protezare', $monthStart->year)
            ->whereMonth('protezare', $monthStart->month)
            ->whereHas('oferte', function ($q) {
                $q->where('oferte.acceptata', Oferta::STATUS_ACCEPTATA);
            });

        if (!$canViewAll) {
            $query->where(function ($q) use ($request) {
                $q->where('user_vanzari', $request->user()->id)
                    ->orWhere('user_tehnic', $request->user()->id);
            });
        } elseif ($selectedUserId > 0) {
            $query->where(function ($q) use ($selectedUserId) {
                $q->where('user_vanzari', $selectedUserId)
                    ->orWhere('user_tehnic', $selectedUserId);
            });
        }

        $fiseCaz = $query->orderByDesc('id')->get();
        $rows = collect();

        foreach ($fiseCaz as $fisaCaz) {
            $ofertaAcceptata = $fisaCaz->oferte->first();
            if (!$ofertaAcceptata) {
                continue;
            }

            $lucrare = $fisaCaz->lucrare;
            if (!$lucrare) {
                $lucrare = $bonusCalculatorService->rezolvaLucrarePentruFisa($fisaCaz);
            }
            if (!$lucrare || !(bool) $lucrare->activ) {
                continue;
            }

            if (empty($fisaCaz->protezare)) {
                continue;
            }

            $valoareOferta = (int) round((float) ($ofertaAcceptata->pret ?? 0));
            $dataPredare = Carbon::parse($fisaCaz->protezare);
            $amputatieFisa = $fisaCaz->latestDateMedicale->amputatie ?? null;
            $interval = $bonusCalculatorService->gasesteIntervalBonus($lucrare->id, $valoareOferta, $dataPredare, $amputatieFisa);

            if (!$interval) {
                continue;
            }

            $roluri = [
                'vanzari' => (int) ($fisaCaz->user_vanzari ?? 0),
                'tehnic' => (int) ($fisaCaz->user_tehnic ?? 0),
            ];

            foreach ($roluri as $rol => $userId) {
                if ($userId <= 0) {
                    continue;
                }

                if (!$canViewAll && $userId !== (int) $request->user()->id) {
                    continue;
                }

                if ($canViewAll && $selectedUserId > 0 && $selectedUserId !== $userId) {
                    continue;
                }

                $bonusFix = (int) $interval->bonus_fix;
                $bonusProcent = (int) $interval->bonus_procent;
                $bonusTotal = $bonusCalculatorService->calculeazaBonusTotal($valoareOferta, $bonusFix, $bonusProcent);

                $rows->push([
                    'fisa_caz_id' => (int) $fisaCaz->id,
                    'pacient_nume' => (string) ($fisaCaz->pacient->nume ?? ''),
                    'pacient_prenume' => (string) ($fisaCaz->pacient->prenume ?? ''),
                    'user_id' => $userId,
                    'user_name' => $rol === 'vanzari'
                        ? (string) ($fisaCaz->userVanzari->name ?? '-')
                        : (string) ($fisaCaz->userTehnic->name ?? '-'),
                    'rol' => $rol,
                    'lucrare_denumire' => (string) $lucrare->denumire,
                    'lucrare_cod' => (string) ($lucrare->cod ?? ''),
                    'amputatie' => $bonusCalculatorService->normalizeAmputatie($interval->amputatie) ?? 'Toate amputatiile',
                    'valoare_oferta' => $valoareOferta,
                    'bonus_fix' => $bonusFix,
                    'bonus_procent' => $bonusProcent,
                    'bonus_total' => $bonusTotal,
                    'pacient_localitate' => (string) ($fisaCaz->pacient->localitate ?? ''),
                    'pacient_judet' => (string) ($fisaCaz->pacient->judet ?? ''),
                ]);
            }
        }

        if ($minUserTotal > 0) {
            $eligibleUserIds = $rows
                ->groupBy('user_id')
                ->filter(function (Collection $userRows) use ($minUserTotal) {
                    return (int) $userRows->sum('bonus_total') >= $minUserTotal;
                })
                ->keys()
                ->all();

            $rows = $rows
                ->filter(function (array $row) use ($eligibleUserIds) {
                    return in_array($row['user_id'], $eligibleUserIds, true);
                })
                ->values();
        }

        return [
            'rows' => $this->sortRows($rows),
            'month' => $month,
            'canViewAll' => $canViewAll,
            'selectedUserId' => $selectedUserId,
            'minUserTotal' => $minUserTotal,
        ];
    }

    protected function sortRows(Collection $rows): Collection
    {
        return $rows
            ->sortBy([
                ['user_name', 'asc'],
                ['pacient_nume', 'asc'],
                ['pacient_prenume', 'asc'],
                ['fisa_caz_id', 'asc'],
                ['rol', 'asc'],
            ])
            ->values();
    }

    protected function downloadTemplateExport(Collection $rows, string $month, string $fileName): StreamedResponse|BinaryFileResponse
    {
        $templatePath = resource_path('templates/bonusuri_template.xlsx');
        if (!file_exists($templatePath)) {
            $templatePath = storage_path('app/templates/bonusuri_template.xlsx');
        }

        if (!file_exists($templatePath)) {
            return \Maatwebsite\Excel\Facades\Excel::download(new BonusuriLunareExport($rows), $fileName);
        }

        $templateRows = $this->buildTemplateRows($rows, $month);
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getSheetByName('GENERAL') ?: $spreadsheet->getSheet(0);
        $sheet->getColumnDimension('J')->setWidth($sheet->getColumnDimension('L')->getWidth());

        $startRow = 9;
        $maxRow = max($startRow, $sheet->getHighestRow());
        $rowsToClear = max(1, $maxRow - $startRow + 1);

        $emptyRows = array_fill(0, $rowsToClear, array_fill(0, 15, ''));
        $sheet->fromArray($emptyRows, null, "A{$startRow}");

        $line = $startRow;
        $sumValoare = 0;
        $sumBonusVanzari = 0;

        foreach ($templateRows as $row) {
            $sheet->duplicateStyle($sheet->getStyle("A{$startRow}:O{$startRow}"), "A{$line}:O{$line}");

            $sheet->setCellValue("A{$line}", $row['nr_crt']);
            $sheet->setCellValue("B{$line}", $row['luna']);
            $sheet->setCellValue("C{$line}", $row['an']);
            $sheet->setCellValue("D{$line}", $row['nume_pacient']);
            $sheet->setCellValue("E{$line}", $row['localitate']);
            $sheet->setCellValue("F{$line}", $row['dispozitiv']);
            $sheet->setCellValue("G{$line}", $row['cod']);
            $sheet->setCellValue("H{$line}", $row['valoare_cu_tva']);
            $sheet->setCellValue("I{$line}", $row['valoare_bonus']);
            $sheet->setCellValue("J{$line}", $row['rm']);
            $sheet->setCellValue("K{$line}", $row['bonus_rm']);
            $sheet->setCellValue("L{$line}", $row['tehnic']);
            $sheet->setCellValue("M{$line}", $row['bonus_tehnic']);
            $sheet->setCellValue("N{$line}", $row['fara_agent']);
            $sheet->setCellValue("O{$line}", $row['observatii']);
            $sheet->getStyle("A{$line}:O{$line}")->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $sheet->getStyle("D{$line}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("F{$line}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("G{$line}")->getFont()->setBold(true);
            $sheet->getStyle("H{$line}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("I{$line}")->getFont()->getColor()->setARGB(Color::COLOR_RED);

            $sumValoare += (int) $row['valoare_cu_tva'];
            $sumBonusVanzari += (int) $row['bonus_rm'];
            $line++;
        }

        $sheet->setCellValue('H4', $sumValoare);
        $sheet->setCellValue('K4', $sumBonusVanzari);
        $sheet->setSelectedCell('A1');
        $sheet->setTopLeftCell('A1');

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    protected function buildTemplateRows(Collection $rows, string $month): Collection
    {
        $monthDate = Carbon::createFromFormat('Y-m', $month)->locale('ro');
        $monthLabel = ucfirst($monthDate->isoFormat('MMMM'));
        $year = (int) $monthDate->year;

        return $rows
            ->groupBy('fisa_caz_id')
            ->map(function (Collection $group, int|string $fisaCazId) use ($monthLabel, $year) {
                $first = $group->first();
                $rowVanzari = $group->firstWhere('rol', 'vanzari');
                $rowTehnic = $group->firstWhere('rol', 'tehnic');
                $localitate = trim((string) ($first['pacient_localitate'] ?? ''));
                if ($localitate === '') {
                    $localitate = trim((string) ($first['pacient_judet'] ?? ''));
                }

                $bonusFix = (int) ($first['bonus_fix'] ?? 0);
                $bonusProcent = (int) ($first['bonus_procent'] ?? 0);
                $valoareBonus = '';
                if ($bonusFix > 0 && $bonusProcent > 0) {
                    $valoareBonus = "Fix {$bonusFix} + {$bonusProcent}%";
                } elseif ($bonusFix > 0) {
                    $valoareBonus = (string) $bonusFix;
                } elseif ($bonusProcent > 0) {
                    $valoareBonus = "{$bonusProcent}%";
                }

                $numePacient = trim((string) ($first['pacient_nume'] ?? '') . ' ' . (string) ($first['pacient_prenume'] ?? ''));

                return [
                    'nr_crt' => 0,
                    'luna' => $monthLabel,
                    'an' => $year,
                    'nume_pacient' => Str::title(mb_strtolower($numePacient)),
                    'localitate' => $localitate,
                    'dispozitiv' => (string) ($first['lucrare_denumire'] ?? ''),
                    'cod' => mb_strtoupper((string) ($first['lucrare_cod'] ?? '')),
                    'valoare_cu_tva' => (int) ($first['valoare_oferta'] ?? 0),
                    'valoare_bonus' => $valoareBonus,
                    'rm' => $rowVanzari['user_name'] ?? '',
                    'bonus_rm' => '',
                    'tehnic' => $rowTehnic['user_name'] ?? '',
                    'bonus_tehnic' => '',
                    'fara_agent' => '',
                    'observatii' => '',
                ];
            })
            ->values()
            ->map(function (array $row, int $index) {
                $row['nr_crt'] = $index + 1;

                return $row;
            });
    }
}
